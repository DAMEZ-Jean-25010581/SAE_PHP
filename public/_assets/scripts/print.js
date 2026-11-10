/*
 * Bouton « Imprimer » de la page de synthèse (#198).
 * Fichier séparé : la CSP du site interdit le JavaScript écrit dans le HTML.
 */
(function () {
    'use strict';
    var button = document.getElementById('print-synthesis');
    if (button) {
        button.addEventListener('click', function () {
            window.print();
        });
    }
})();
