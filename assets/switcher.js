/**
 * switcher.js — Enhanced Language Switcher interactions
 *
 * مسؤول فقط عن: فتح/إغلاق الـ dropdown، التنقل بالكيبورد، auto-align.
 * لا يلمس منطق الترجمة ولا runtime.js الأصلي.
 */
( function () {
    'use strict';

    /* ── Init ──────────────────────────────────────────── */
    function initSwitchers() {
        document.querySelectorAll(
            '.i18n-language-switcher.dropdown:not([data-switcher-init])'
        ).forEach( function ( wrapper ) {
            wrapper.setAttribute( 'data-switcher-init', '1' );
            buildTrigger( wrapper );
            bindEvents( wrapper );
        } );
    }

    /* ── Replace <span class="current-language"> with <button> ── */
    function buildTrigger( wrapper ) {
        var legacy = wrapper.querySelector( '.current-language' );
        if ( ! legacy ) return; // Already enhanced or custom markup.

        var btn = document.createElement( 'button' );
        btn.type = 'button';
        btn.className = 'i18n-switcher-trigger';
        btn.setAttribute( 'aria-haspopup', 'listbox' );
        btn.setAttribute( 'aria-expanded', 'false' );

        // Move existing content (flag + name) into the button.
        while ( legacy.firstChild ) {
            btn.appendChild( legacy.firstChild );
        }

        // Append caret.
        var caret = document.createElement( 'span' );
        caret.className = 'i18n-switcher-caret';
        caret.setAttribute( 'aria-hidden', 'true' );
        btn.appendChild( caret );

        legacy.parentNode.replaceChild( btn, legacy );

        // Add listbox role + id to <ul>.
        var ul = wrapper.querySelector( 'ul' );
        if ( ul ) {
            ul.className = ( ul.className + ' i18n-switcher-menu' ).trim();
            ul.setAttribute( 'role', 'listbox' );
            var menuId = 'i18n-menu-' + Math.random().toString( 36 ).slice( 2 );
            ul.id = menuId;
            btn.setAttribute( 'aria-controls', menuId );

            // Add lang + hreflang to every link.
            ul.querySelectorAll( 'li a' ).forEach( function ( a ) {
                var liClass = a.parentElement ? a.parentElement.className : '';
                var code    = ( a.querySelector( '[class*="flag-"]' ) || {} ).className || '';
                var match   = code.match( /flag-([a-z]{2,})/ );
                if ( match ) {
                    a.setAttribute( 'lang',      match[1] );
                    a.setAttribute( 'hreflang',  match[1] );
                }
            } );
        }
    }

    /* ── Events ────────────────────────────────────────── */
    function bindEvents( wrapper ) {
        var trigger = wrapper.querySelector( '.i18n-switcher-trigger' );
        if ( ! trigger ) return;

        // Click on trigger → toggle.
        trigger.addEventListener( 'click', function ( e ) {
            e.stopPropagation();
            var isOpen = wrapper.classList.contains( 'is-open' );
            closeAll();
            if ( ! isOpen ) openDropdown( wrapper, trigger );
        } );

        // Keyboard inside wrapper.
        wrapper.addEventListener( 'keydown', function ( e ) {
            if ( ! wrapper.classList.contains( 'is-open' ) ) return;
            var links = Array.from(
                wrapper.querySelectorAll( '.i18n-switcher-menu a' )
            );
            var idx = links.indexOf( document.activeElement );

            if ( e.key === 'ArrowDown' ) {
                e.preventDefault();
                links[ ( idx + 1 ) % links.length ].focus();
            } else if ( e.key === 'ArrowUp' ) {
                e.preventDefault();
                links[ ( idx - 1 + links.length ) % links.length ].focus();
            } else if ( e.key === 'Escape' ) {
                closeDropdown( wrapper, trigger );
                trigger.focus();
            }
        } );
    }

    /* ── Open / close ──────────────────────────────────── */
    function openDropdown( wrapper, trigger ) {
        wrapper.classList.add( 'is-open' );
        trigger.setAttribute( 'aria-expanded', 'true' );
        autoAlign( wrapper );
    }

    function closeDropdown( wrapper, trigger ) {
        wrapper.classList.remove( 'is-open' );
        if ( trigger ) trigger.setAttribute( 'aria-expanded', 'false' );
    }

    function closeAll() {
        document.querySelectorAll( '.i18n-language-switcher.dropdown.is-open' )
            .forEach( function ( w ) {
                closeDropdown( w, w.querySelector( '.i18n-switcher-trigger' ) );
            } );
    }

    /* ── Auto-align (flip when near right edge) ────────── */
    function autoAlign( wrapper ) {
        wrapper.classList.remove( 'align-right' );
        var rect = wrapper.getBoundingClientRect();
        var menu = wrapper.querySelector( '.i18n-switcher-menu' );
        if ( ! menu ) return;
        var menuW = menu.offsetWidth || 180;
        if ( rect.left + menuW > window.innerWidth - 8 ) {
            wrapper.classList.add( 'align-right' );
        }
    }

    /* ── Global close on outside click / focus ─────────── */
    document.addEventListener( 'click', closeAll );

    document.addEventListener( 'focusin', function ( e ) {
        document.querySelectorAll(
            '.i18n-language-switcher.dropdown.is-open'
        ).forEach( function ( w ) {
            if ( ! w.contains( e.target ) ) {
                closeDropdown( w, w.querySelector( '.i18n-switcher-trigger' ) );
            }
        } );
    } );

    /* ── Run after DOM ready ────────────────────────────── */
    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', initSwitchers );
    } else {
        initSwitchers();
    }

    /* ── Re-run on dynamic content (Gutenberg / AJAX) ──── */
    if ( typeof MutationObserver !== 'undefined' ) {
        new MutationObserver( function ( mutations ) {
            mutations.forEach( function ( m ) {
                m.addedNodes.forEach( function ( node ) {
                    if ( node.nodeType !== 1 ) return;
                    if ( node.matches && node.matches( '.i18n-language-switcher.dropdown' ) ) {
                        initSwitchers();
                    } else if ( node.querySelector ) {
                        if ( node.querySelector( '.i18n-language-switcher.dropdown' ) ) {
                            initSwitchers();
                        }
                    }
                } );
            } );
        } ).observe( document.body, { childList: true, subtree: true } );
    }

} )();
