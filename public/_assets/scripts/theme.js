/*
 * Mode clair / sombre (issue #204).
 * - Chargé dans le <head> pour appliquer le thème avant l'affichage (pas de « flash »).
 * - Le choix est mémorisé dans le navigateur (localStorage).
 * - Sans choix enregistré, on suit le réglage du système (prefers-color-scheme).
 */
(function () {
    'use strict';

    var KEY = 'cyberlab-theme';
    var root = document.documentElement;

    function savedTheme() {
        try {
            var value = localStorage.getItem(KEY);
            return value === 'light' || value === 'dark' ? value : null;
        } catch (e) {
            return null; // navigation privée ou stockage bloqué
        }
    }

    function systemTheme() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
    }

    function apply(theme) {
        if (theme === 'light') {
            root.setAttribute('data-theme', 'light');
        } else {
            root.removeAttribute('data-theme');
        }
        updateButton(theme);
    }

    // Le bouton propose le thème inverse : soleil en mode sombre, lune en mode clair
    function updateButton(theme) {
        var button = document.getElementById('mode-toggle');
        if (!button) {
            return;
        }
        var label = theme === 'light' ? 'Passer en mode sombre' : 'Passer en mode clair';
        var icon = button.querySelector('img');
        button.setAttribute('aria-label', label);
        button.setAttribute('title', label);
        if (icon) {
            icon.src = '/_assets/images/icons/' + (theme === 'light' ? 'Moon_icon.svg' : 'Light_mode_icon.svg');
            icon.alt = label;
        }
    }

    var current = savedTheme() || systemTheme();
    apply(current);

    document.addEventListener('DOMContentLoaded', function () {
        updateButton(current);
        var button = document.getElementById('mode-toggle');
        if (!button) {
            return;
        }
        button.addEventListener('click', function () {
            current = current === 'light' ? 'dark' : 'light';
            try {
                localStorage.setItem(KEY, current);
            } catch (e) { /* le thème change quand même pour cette page */ }
            apply(current);
        });
    });
})();
