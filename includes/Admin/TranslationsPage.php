<?php

namespace I18nTranslate\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class TranslationsPage {
	private const SLUG = 'i18n-translate-translations';

	public function render(): void {
		if ( ! current_user_can( 'i18n_translate_translate' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'i18n-translate' ) );
		}

		$languages = $this->get_enabled_languages();
		$strings = $this->get_all_strings();
		$domains = $this->get_domains();
		
		$languages_json = wp_json_encode( $languages );
		$strings_json = wp_json_encode( $strings );
		$domains_json = wp_json_encode( $domains );
		?>
		<div class="wrap i18n-admin" x-data="translationsApp(<?php echo esc_attr( $languages_json ); ?>, <?php echo esc_attr( $strings_json ); ?>, <?php echo esc_attr( $domains_json ); ?>)">
			<div class="i18n-header">
				<h1><?php esc_html_e( 'Translations', 'i18n-translate' ); ?></h1>
			</div>

			<!-- Success/Error Messages -->
			<div x-show="message" x-cloak x-transition class="i18n-notice" :class="messageType === 'success' ? 'i18n-notice-success' : 'i18n-notice-error'">
				<span x-text="message"></span>
				<button type="button" @click="message = ''">&times;</button>
			</div>

			<!-- Language Tabs -->
			<div class="i18n-tabs">
				<template x-for="lang in languages" :key="lang.code">
					<button type="button" 
						class="i18n-tab" 
						:class="{ 'i18n-tab-active': currentLang === lang.code }"
						@click="setLang(lang.code)">
						<span class="i18n-tab-flag" x-text="lang.flag || '🌐'"></span>
						<span class="i18n-tab-name" x-text="lang.name"></span>
						<span class="i18n-text-muted" style="margin-left: 6px;" x-show="statsByLang?.[lang.code]">
							(<span x-text="statsByLang?.[lang.code]?.translated || 0"></span>/<span x-text="statsByLang?.[lang.code]?.total || 0"></span>)
						</span>
					</button>
				</template>
			</div>

			<!-- Filters & Actions Bar -->
			<div class="i18n-toolbar">
				<div class="i18n-toolbar-left">
					<div class="i18n-search-box">
						<span class="dashicons dashicons-search"></span>
						<input type="text" 
							x-model="searchQuery" 
							placeholder="<?php esc_attr_e( 'Search keys or translations...', 'i18n-translate' ); ?>"
							@input="filterStrings()">
					</div>
					<select x-model="filterDomain" @change="filterStrings()" class="i18n-select">
						<option value=""><?php esc_html_e( 'All Domains', 'i18n-translate' ); ?></option>
						<template x-for="domain in domains" :key="domain">
							<option :value="domain" x-text="domain"></option>
						</template>
					</select>
					<select x-model="filterStatus" @change="filterStrings()" class="i18n-select">
						<option value=""><?php esc_html_e( 'All Status', 'i18n-translate' ); ?></option>
						<option value="translated"><?php esc_html_e( 'Translated', 'i18n-translate' ); ?></option>
						<option value="missing"><?php esc_html_e( 'Missing', 'i18n-translate' ); ?></option>
					</select>
				</div>
				<div class="i18n-toolbar-right">
					<button type="button" class="i18n-btn i18n-btn-secondary" @click="showStringsModal = true">
						<span class="dashicons dashicons-edit"></span>
						<?php esc_html_e( 'Manage Strings', 'i18n-translate' ); ?>
					</button>
					<button type="button" class="i18n-btn i18n-btn-secondary" @click="exportJson()">
						<span class="dashicons dashicons-download"></span>
						<?php esc_html_e( 'Export', 'i18n-translate' ); ?>
					</button>
					<button type="button" class="i18n-btn i18n-btn-secondary" @click="showImportModal = true">
						<span class="dashicons dashicons-upload"></span>
						<?php esc_html_e( 'Import', 'i18n-translate' ); ?>
					</button>
				</div>
			</div>

			<!-- Translations Table -->
			<div class="i18n-table-wrapper">
				<table class="i18n-table">
					<thead>
						<tr>
							<th class="i18n-th-key"><?php esc_html_e( 'Key', 'i18n-translate' ); ?></th>
							<th class="i18n-th-domain"><?php esc_html_e( 'Domain', 'i18n-translate' ); ?></th>
							<th class="i18n-th-default"><?php esc_html_e( 'Default', 'i18n-translate' ); ?></th>
							<th class="i18n-th-translation"><?php esc_html_e( 'Translation', 'i18n-translate' ); ?></th>
							<th class="i18n-th-actions"><?php esc_html_e( 'Actions', 'i18n-translate' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<template x-for="str in filteredStrings" :key="str.id + '-' + currentLang">
							<tr :class="{ 'i18n-row-missing': !getTranslation(str.id) }">
								<td class="i18n-td-key">
									<code x-text="str.string_key"></code>
								</td>
								<td class="i18n-td-domain">
									<span class="i18n-badge i18n-badge-domain" x-text="str.domain"></span>
								</td>
								<td class="i18n-td-default">
									<span x-text="str.default_text || '—'"></span>
								</td>
								<td class="i18n-td-translation">
									<div class="i18n-inline-edit" x-data="{ editing: false, value: '' }" x-init="value = getTranslation(str.id) || ''" x-effect="if (!editing) { value = getTranslation(str.id) || ''; }">
										<template x-if="!editing">
											<div class="i18n-inline-display" @click="editing = true; value = getTranslation(str.id) || ''; $root.beginEdit(str.id, value); $nextTick(() => $refs.input?.focus())">
												<span x-show="value" x-text="value"></span>
												<span x-show="!value" class="i18n-text-muted"><?php esc_html_e( 'Click to add translation...', 'i18n-translate' ); ?></span>
											</div>
										</template>
										<template x-if="editing">
											<div class="i18n-inline-input">
												<textarea 
													x-ref="input"
													x-model="value" 
													rows="2"
													@input="$root.setEditDirty(str.id, value)"
													@keydown.escape="editing = false; $root.endEdit(str.id)"
													@keydown.ctrl.enter="saveTranslation(str.id, value); editing = false; $root.endEdit(str.id)"></textarea>
												<div class="i18n-inline-actions">
													<button type="button" class="i18n-btn i18n-btn-sm i18n-btn-primary" @click="saveTranslation(str.id, value); editing = false; $root.endEdit(str.id)">
														<span class="dashicons dashicons-yes"></span>
													</button>
													<button type="button" class="i18n-btn i18n-btn-sm i18n-btn-secondary" @click="editing = false; $root.endEdit(str.id)">
														<span class="dashicons dashicons-no"></span>
													</button>
												</div>
											</div>
										</template>
									</div>
								</td>
								<td class="i18n-td-actions">
									<span class="i18n-status" :class="getTranslation(str.id) ? 'i18n-status-done' : 'i18n-status-missing'">
										<span class="dashicons" :class="getTranslation(str.id) ? 'dashicons-yes-alt' : 'dashicons-warning'"></span>
									</span>
								</td>
							</tr>
						</template>
					</tbody>
				</table>

				<!-- Empty State -->
				<div x-show="filteredStrings.length === 0" class="i18n-empty-state">
					<span class="dashicons dashicons-admin-site-alt3"></span>
					<p x-show="strings.length === 0"><?php esc_html_e( 'No translation strings found.', 'i18n-translate' ); ?></p>
					<p x-show="strings.length > 0 && filteredStrings.length === 0"><?php esc_html_e( 'No strings match your filters.', 'i18n-translate' ); ?></p>
					<button x-show="strings.length === 0" type="button" class="i18n-btn i18n-btn-primary" @click="showStringsModal = true">
						<?php esc_html_e( 'Add Translation Strings', 'i18n-translate' ); ?>
					</button>
				</div>

				<!-- Pagination -->
				<div class="i18n-pagination" x-show="totalPages > 1">
					<button type="button" class="i18n-btn i18n-btn-sm" :disabled="currentPage === 1" @click="currentPage--; filterStrings()">
						&larr; <?php esc_html_e( 'Previous', 'i18n-translate' ); ?>
					</button>
					<span class="i18n-page-info">
						<?php esc_html_e( 'Page', 'i18n-translate' ); ?> <span x-text="currentPage"></span> <?php esc_html_e( 'of', 'i18n-translate' ); ?> <span x-text="totalPages"></span>
					</span>
					<button type="button" class="i18n-btn i18n-btn-sm" :disabled="currentPage >= totalPages" @click="currentPage++; filterStrings()">
						<?php esc_html_e( 'Next', 'i18n-translate' ); ?> &rarr;
					</button>
				</div>
			</div>

			<!-- Manage Strings Modal -->
			<div class="i18n-modal-overlay" x-show="showStringsModal" x-transition.opacity @click.self="showStringsModal = false">
				<div class="i18n-modal i18n-modal-lg" @click.stop>
					<div class="i18n-modal-header">
						<h2><?php esc_html_e( 'Manage Translation Strings', 'i18n-translate' ); ?></h2>
						<button type="button" class="i18n-modal-close" @click="showStringsModal = false">&times;</button>
					</div>
					<div class="i18n-modal-body">
						<!-- Add New String Form -->
						<div class="i18n-section">
							<h3><?php esc_html_e( 'Add New String', 'i18n-translate' ); ?></h3>
							<form @submit.prevent="addString()" class="i18n-add-string-form">
								<div class="i18n-form-row">
									<div class="i18n-form-group">
										<label><?php esc_html_e( 'Domain', 'i18n-translate' ); ?></label>
										<input type="text" x-model="newString.domain" placeholder="default" list="domains-list">
										<datalist id="domains-list">
											<template x-for="d in domains" :key="d">
												<option :value="d"></option>
											</template>
										</datalist>
									</div>
									<div class="i18n-form-group i18n-form-group-grow">
										<label><?php esc_html_e( 'Key', 'i18n-translate' ); ?> <span class="required">*</span></label>
										<input type="text" x-model="newString.key" placeholder="button.submit" required>
									</div>
								</div>
								<div class="i18n-form-group">
									<label><?php esc_html_e( 'Default Text', 'i18n-translate' ); ?></label>
									<textarea x-model="newString.default_text" rows="2" placeholder="<?php esc_attr_e( 'Default text (usually English)', 'i18n-translate' ); ?>"></textarea>
								</div>
								<button type="submit" class="i18n-btn i18n-btn-primary" :disabled="addingString">
									<span x-show="addingString" class="i18n-spinner"></span>
									<?php esc_html_e( 'Add String', 'i18n-translate' ); ?>
								</button>
							</form>
						</div>

						<hr class="i18n-divider">

						<!-- Bulk Add Strings -->
						<div class="i18n-section">
							<h3><?php esc_html_e( 'Bulk Add Strings', 'i18n-translate' ); ?></h3>
							<p class="i18n-text-muted"><?php esc_html_e( 'Add multiple keys at once. One key per line, or paste JSON format.', 'i18n-translate' ); ?></p>
							<div class="i18n-form-group">
								<label><?php esc_html_e( 'Domain for bulk add', 'i18n-translate' ); ?></label>
								<input type="text" x-model="bulkDomain" placeholder="default">
							</div>
							<div class="i18n-form-group">
								<label><?php esc_html_e( 'Keys (one per line or JSON)', 'i18n-translate' ); ?></label>
								<textarea x-model="bulkKeys" rows="6" placeholder='button.submit
button.cancel
page.title

<?php esc_attr_e( 'Or JSON format:', 'i18n-translate' ); ?>
{"key": "default text", "another.key": "Another default"}'></textarea>
							</div>
							<button type="button" class="i18n-btn i18n-btn-secondary" @click="bulkAddStrings()" :disabled="bulkAdding">
								<span x-show="bulkAdding" class="i18n-spinner"></span>
								<?php esc_html_e( 'Add All', 'i18n-translate' ); ?>
							</button>
						</div>

						<hr class="i18n-divider">

						<!-- Existing Strings List -->
						<div class="i18n-section">
							<h3><?php esc_html_e( 'Existing Strings', 'i18n-translate' ); ?> (<span x-text="strings.length"></span>)</h3>
							<div class="i18n-strings-list">
								<template x-for="str in strings" :key="str.id">
									<div class="i18n-string-item" x-data="{ editing: false }">
										<template x-if="!editing">
											<div class="i18n-string-display">
												<div class="i18n-string-info">
													<span class="i18n-badge i18n-badge-domain" x-text="str.domain"></span>
													<code x-text="str.string_key"></code>
													<span class="i18n-text-muted" x-text="str.default_text ? '= ' + str.default_text.substring(0, 50) + (str.default_text.length > 50 ? '...' : '') : ''"></span>
												</div>
												<div class="i18n-string-actions">
													<button type="button" class="i18n-btn i18n-btn-sm i18n-btn-secondary" @click="editing = true">
														<span class="dashicons dashicons-edit"></span>
													</button>
													<button type="button" class="i18n-btn i18n-btn-sm i18n-btn-danger" @click="confirmDeleteString(str)">
														<span class="dashicons dashicons-trash"></span>
													</button>
												</div>
											</div>
										</template>
										<template x-if="editing">
											<div class="i18n-string-edit-form">
												<div class="i18n-form-row">
													<div class="i18n-form-group">
														<input type="text" x-model="str.domain" placeholder="domain">
													</div>
													<div class="i18n-form-group i18n-form-group-grow">
														<input type="text" x-model="str.string_key" placeholder="key">
													</div>
												</div>
												<div class="i18n-form-group">
													<textarea x-model="str.default_text" rows="2" placeholder="default text"></textarea>
												</div>
												<div class="i18n-inline-actions">
													<button type="button" class="i18n-btn i18n-btn-sm i18n-btn-primary" @click="updateString(str); editing = false">
														<?php esc_html_e( 'Save', 'i18n-translate' ); ?>
													</button>
													<button type="button" class="i18n-btn i18n-btn-sm i18n-btn-secondary" @click="editing = false">
														<?php esc_html_e( 'Cancel', 'i18n-translate' ); ?>
													</button>
												</div>
											</div>
										</template>
									</div>
								</template>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Import Modal -->
			<div class="i18n-modal-overlay" x-show="showImportModal" x-transition.opacity @click.self="showImportModal = false">
				<div class="i18n-modal" @click.stop>
					<div class="i18n-modal-header">
						<h2><?php esc_html_e( 'Import Translations', 'i18n-translate' ); ?></h2>
						<button type="button" class="i18n-modal-close" @click="showImportModal = false">&times;</button>
					</div>
					<form @submit.prevent="importJson()">
						<div class="i18n-modal-body">
							<p><?php esc_html_e( 'Import translations from a JSON file. Format:', 'i18n-translate' ); ?></p>
							<pre class="i18n-code-sample">{"domain": {"key": "translation", ...}}</pre>
							<div class="i18n-form-group">
								<label><?php esc_html_e( 'JSON File', 'i18n-translate' ); ?></label>
								<input type="file" accept=".json" @change="importFile = $event.target.files[0]" required>
							</div>
							<p class="i18n-text-muted"><?php esc_html_e( 'Translations will be imported for the currently selected language.', 'i18n-translate' ); ?></p>
						</div>
						<div class="i18n-modal-footer">
							<button type="button" class="i18n-btn i18n-btn-secondary" @click="showImportModal = false"><?php esc_html_e( 'Cancel', 'i18n-translate' ); ?></button>
							<button type="submit" class="i18n-btn i18n-btn-primary" :disabled="importing">
								<span x-show="importing" class="i18n-spinner"></span>
								<?php esc_html_e( 'Import', 'i18n-translate' ); ?>
							</button>
						</div>
					</form>
				</div>
			</div>

			<!-- Delete String Confirmation -->
			<div class="i18n-modal-overlay" x-show="showDeleteStringModal" x-transition.opacity @click.self="showDeleteStringModal = false">
				<div class="i18n-modal i18n-modal-sm" @click.stop>
					<div class="i18n-modal-header i18n-modal-header-danger">
						<h2><?php esc_html_e( 'Delete String', 'i18n-translate' ); ?></h2>
						<button type="button" class="i18n-modal-close" @click="showDeleteStringModal = false">&times;</button>
					</div>
					<div class="i18n-modal-body">
						<p><?php esc_html_e( 'Delete this translation string?', 'i18n-translate' ); ?></p>
						<p><code x-text="deletingString?.string_key"></code></p>
						<p class="i18n-text-muted"><?php esc_html_e( 'All translations for this key will also be deleted.', 'i18n-translate' ); ?></p>
					</div>
					<div class="i18n-modal-footer">
						<button type="button" class="i18n-btn i18n-btn-secondary" @click="showDeleteStringModal = false"><?php esc_html_e( 'Cancel', 'i18n-translate' ); ?></button>
						<button type="button" class="i18n-btn i18n-btn-danger" @click="deleteString()" :disabled="deletingStringLoading">
							<?php esc_html_e( 'Delete', 'i18n-translate' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>

		<script>
		function translationsApp(languages, strings, domains) {
			return {
				languages: languages || [],
				strings: strings || [],
				domains: domains || ['default'],
				currentLang: languages[0]?.code || 'en',
				translations: {},
				statsByLang: {},
				editingState: { stringId: null, initial: '', dirty: false },
				searchQuery: '',
				filterDomain: '',
				filterStatus: '',

                                recomputeStats() {
                                        this.statsByLang = {};
                                        this.languages.forEach(lang => {
                                                this.statsByLang[lang.code] = { total: 0, translated: 0 };
                                        });

                                        this.strings.forEach(str => {
                                                this.languages.forEach(lang => {
                                                        this.statsByLang[lang.code].total++;
                                                        if (str.translations && str.translations[lang.code]) {
                                                                this.statsByLang[lang.code].translated++;
                                                        }
                                                });
                                        });
                                },
				filteredStrings: [],
				currentPage: 1,
				perPage: 25,
				totalPages: 1,
				message: '',
				messageType: 'success',
				showStringsModal: false,
				showImportModal: false,
				showDeleteStringModal: false,
				newString: { domain: 'default', key: '', default_text: '' },
				addingString: false,
				bulkDomain: 'default',
				bulkKeys: '',
				bulkAdding: false,
				deletingString: null,
				deletingStringLoading: false,
				importFile: null,
				importing: false,

				init() {
					// Restore last selected language if still enabled
					try {
						const saved = localStorage.getItem('i18nTranslateAdminLang');
						if (saved && this.languages.some(l => l.code === saved)) {
							this.currentLang = saved;
						}
					} catch (e) {}

					this.recomputeStats();
					this.loadTranslations();
					this.filterStrings();

					this.$watch('currentLang', (value) => {
						try { localStorage.setItem('i18nTranslateAdminLang', value); } catch (e) {}
						this.currentPage = 1;
						this.loadTranslations();
						this.filterStrings();
					});
				},

				setLang(code) {
					if (code === this.currentLang) return;
					if (this.editingState?.dirty) {
						const ok = confirm('<?php echo esc_js( __( 'You have unsaved changes. Discard them and switch language?', 'i18n-translate' ) ); ?>');
						if (!ok) return;
					}
					this.editingState = { stringId: null, initial: '', dirty: false };
					this.currentLang = code;
				},

				beginEdit(stringId, initialValue) {
					this.editingState = { stringId: stringId, initial: (initialValue || ''), dirty: false };
				},

				setEditDirty(stringId, currentValue) {
					if (!this.editingState || this.editingState.stringId !== stringId) return;
					this.editingState.dirty = (String(currentValue || '') !== String(this.editingState.initial || ''));
				},

				endEdit(stringId) {
					if (!this.editingState || this.editingState.stringId !== stringId) return;
					this.editingState = { stringId: null, initial: '', dirty: false };
				},


				async loadTranslations() {
					// Load translations for current language from strings data
					this.translations = {};
					this.strings.forEach(str => {
						if (str.translations && str.translations[this.currentLang]) {
							this.translations[str.id] = str.translations[this.currentLang];
						}
					});
				},

				getTranslation(stringId) {
					const str = this.strings.find(s => s.id === stringId);
					return str?.translations?.[this.currentLang] || '';
				},

				filterStrings() {
					let result = [...this.strings];

					if (this.searchQuery) {
						const q = this.searchQuery.toLowerCase();
						result = result.filter(s => 
							s.string_key.toLowerCase().includes(q) || 
							(s.default_text && s.default_text.toLowerCase().includes(q)) ||
							(s.translations?.[this.currentLang] && s.translations[this.currentLang].toLowerCase().includes(q))
						);
					}

					if (this.filterDomain) {
						result = result.filter(s => s.domain === this.filterDomain);
					}

					if (this.filterStatus === 'translated') {
						result = result.filter(s => s.translations?.[this.currentLang]);
					} else if (this.filterStatus === 'missing') {
						result = result.filter(s => !s.translations?.[this.currentLang]);
					}

					this.totalPages = Math.max(1, Math.ceil(result.length / this.perPage));
					if (this.currentPage > this.totalPages) {
						this.currentPage = 1;
					}
					const start = (this.currentPage - 1) * this.perPage;
					this.filteredStrings = result.slice(start, start + this.perPage);
				},

				async saveTranslation(stringId, value) {
					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_save_translation',
								nonce: i18nTranslate.nonce,
								string_id: stringId,
								lang_code: this.currentLang,
								translation: value
							})
						});
						const data = await response.json();
						if (data.success) {
							const str = this.strings.find(s => s.id === stringId);
							if (str) {
								if (!str.translations) str.translations = {};
								str.translations[this.currentLang] = value;
							}
							this.recomputeStats();
							this.filterStrings();
							this.showMessage('<?php echo esc_js( __( 'Translation saved', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error saving', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
				},

				async addString() {
					if (!this.newString.key) return;
					this.addingString = true;
					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_save_string',
								nonce: i18nTranslate.nonce,
								string_id: 0,
								domain: this.newString.domain || 'default',
								string_key: this.newString.key,
								default_text: this.newString.default_text || ''
							})
						});
						const data = await response.json();
						if (data.success) {
							this.strings.push({
								id: data.data.string_id,
								domain: this.newString.domain || 'default',
								string_key: this.newString.key,
								default_text: this.newString.default_text || '',
								translations: {}
							});
							if (!this.domains.includes(this.newString.domain || 'default')) {
								this.domains.push(this.newString.domain || 'default');
							}
							this.newString = { domain: 'default', key: '', default_text: '' };
							this.recomputeStats();
							this.filterStrings();
							this.showMessage('<?php echo esc_js( __( 'String added', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error adding string', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
					this.addingString = false;
				},

				async bulkAddStrings() {
					if (!this.bulkKeys.trim()) return;
					this.bulkAdding = true;

					let keys = [];
					const trimmed = this.bulkKeys.trim();
					
					// Try to parse as JSON first
					if (trimmed.startsWith('{')) {
						try {
							const json = JSON.parse(trimmed);
							for (const [key, value] of Object.entries(json)) {
								keys.push({ key, default_text: typeof value === 'string' ? value : '' });
							}
						} catch (e) {
							this.showMessage('<?php echo esc_js( __( 'Invalid JSON format', 'i18n-translate' ) ); ?>', 'error');
							this.bulkAdding = false;
							return;
						}
					} else {
						// Parse as line-separated keys
						keys = trimmed.split('\n')
							.map(line => line.trim())
							.filter(line => line && !line.startsWith('#') && !line.startsWith('//'))
							.map(key => ({ key, default_text: '' }));
					}

					let added = 0;
					for (const item of keys) {
						try {
							const response = await fetch(i18nTranslate.ajaxUrl, {
								method: 'POST',
								headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
								body: new URLSearchParams({
									action: 'i18n_save_string',
									nonce: i18nTranslate.nonce,
									string_id: 0,
									domain: this.bulkDomain || 'default',
									string_key: item.key,
									default_text: item.default_text
								})
							});
							const data = await response.json();
							if (data.success) {
								this.strings.push({
									id: data.data.string_id,
									domain: this.bulkDomain || 'default',
									string_key: item.key,
									default_text: item.default_text,
									translations: {}
								});
								added++;
							}
						} catch (e) {}
					}

					if (!this.domains.includes(this.bulkDomain || 'default')) {
						this.domains.push(this.bulkDomain || 'default');
					}
					this.bulkKeys = '';
					this.recomputeStats();
					this.filterStrings();
					this.showMessage(`<?php echo esc_js( __( 'Added', 'i18n-translate' ) ); ?> ${added} <?php echo esc_js( __( 'strings', 'i18n-translate' ) ); ?>`, 'success');
					this.bulkAdding = false;
				},

				async updateString(str) {
					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_save_string',
								nonce: i18nTranslate.nonce,
								string_id: str.id,
								domain: str.domain,
								string_key: str.string_key,
								default_text: str.default_text || ''
							})
						});
						const data = await response.json();
						if (data.success) {
							this.filterStrings();
							this.showMessage('<?php echo esc_js( __( 'String updated', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error updating', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
				},

				confirmDeleteString(str) {
					this.deletingString = str;
					this.showDeleteStringModal = true;
				},

				async deleteString() {
					if (!this.deletingString) return;
					this.deletingStringLoading = true;
					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_delete_string',
								nonce: i18nTranslate.nonce,
								string_id: this.deletingString.id
							})
						});
						const data = await response.json();
						if (data.success) {
							this.strings = this.strings.filter(s => s.id !== this.deletingString.id);
							this.showDeleteStringModal = false;
							this.deletingString = null;
							this.recomputeStats();
							this.filterStrings();
							this.showMessage('<?php echo esc_js( __( 'String deleted', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error deleting', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
					this.deletingStringLoading = false;
				},

				exportJson() {
					const data = {};
					this.strings.forEach(str => {
						if (!data[str.domain]) data[str.domain] = {};
						data[str.domain][str.string_key] = str.translations?.[this.currentLang] || str.default_text || str.string_key;
					});
					const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
					const url = URL.createObjectURL(blob);
					const a = document.createElement('a');
					a.href = url;
					a.download = `translations-${this.currentLang}.json`;
					a.click();
					URL.revokeObjectURL(url);
				},

				async importJson() {
					if (!this.importFile) return;
					this.importing = true;
					try {
						const text = await this.importFile.text();
						const json = JSON.parse(text);
						
						let imported = 0;
						for (const [domain, keys] of Object.entries(json)) {
							if (typeof keys !== 'object') continue;
							for (const [key, value] of Object.entries(keys)) {
								// Find or create string
								let str = this.strings.find(s => s.domain === domain && s.string_key === key);
								if (!str) {
									// Create string first
									const createRes = await fetch(i18nTranslate.ajaxUrl, {
										method: 'POST',
										headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
										body: new URLSearchParams({
											action: 'i18n_save_string',
											nonce: i18nTranslate.nonce,
											string_id: 0,
											domain: domain,
											string_key: key,
											default_text: ''
										})
									});
									const createData = await createRes.json();
									if (createData.success) {
										str = {
											id: createData.data.string_id,
											domain: domain,
											string_key: key,
											default_text: '',
											translations: {}
										};
										this.strings.push(str);
									}
								}
								if (str) {
									// Save translation
									await fetch(i18nTranslate.ajaxUrl, {
										method: 'POST',
										headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
										body: new URLSearchParams({
											action: 'i18n_save_translation',
											nonce: i18nTranslate.nonce,
											string_id: str.id,
											lang_code: this.currentLang,
											translation: value
										})
									});
									if (!str.translations) str.translations = {};
									str.translations[this.currentLang] = value;
									imported++;
								}
							}
						}

						this.showImportModal = false;
						this.importFile = null;
						this.recomputeStats();
						this.filterStrings();
						this.showMessage(`<?php echo esc_js( __( 'Imported', 'i18n-translate' ) ); ?> ${imported} <?php echo esc_js( __( 'translations', 'i18n-translate' ) ); ?>`, 'success');
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Error parsing JSON', 'i18n-translate' ) ); ?>', 'error');
					}
					this.importing = false;
				},

				showMessage(msg, type) {
					this.message = msg;
					this.messageType = type;
					setTimeout(() => { this.message = ''; }, 4000);
				}
			};
		}
		</script>
		<?php
	}

	private function get_enabled_languages(): array {
		global $wpdb;
		$table = $wpdb->prefix . 'i18n_languages';

		$rows = $wpdb->get_results( "SELECT code, locale, name, native_name, rtl, flag FROM {$table} WHERE enabled = 1 ORDER BY sort_order ASC", ARRAY_A );
		return array_values( $rows );
	}

	private function get_all_strings(): array {
		global $wpdb;
		$strings_table = $wpdb->prefix . 'i18n_strings';
		$tr_table = $wpdb->prefix . 'i18n_translations';

		$strings = $wpdb->get_results( "SELECT id, domain, string_key, default_text FROM {$strings_table} ORDER BY domain, string_key ASC", ARRAY_A );

		// Get all translations
		$translations = $wpdb->get_results( "SELECT string_id, lang_code, translation_text FROM {$tr_table}", ARRAY_A );
		$tr_map = [];
		foreach ( $translations as $tr ) {
			$sid = (int) $tr['string_id'];
			if ( ! isset( $tr_map[ $sid ] ) ) {
				$tr_map[ $sid ] = [];
			}
			$tr_map[ $sid ][ $tr['lang_code'] ] = $tr['translation_text'];
		}

		// Attach translations to strings
		foreach ( $strings as &$str ) {
			$str['id'] = (int) $str['id'];
			$str['translations'] = $tr_map[ $str['id'] ] ?? [];
		}

		return $strings;
	}

	private function get_domains(): array {
		global $wpdb;
		$table = $wpdb->prefix . 'i18n_strings';
		$domains = $wpdb->get_col( "SELECT DISTINCT domain FROM {$table} ORDER BY domain ASC" );
		return $domains ?: [ 'default' ];
	}
}
