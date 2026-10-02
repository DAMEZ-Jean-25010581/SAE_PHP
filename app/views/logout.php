<?php
use SAE_PHP\views\Layout;

ob_start();
?>
<div class="auth-wrapper">
    <div class="auth-card" style="text-align: center;">
        <div class="auth-header">
            <h1 class="auth-title">DÉCONNEXION</h1>
            <p class="auth-subtitle">&gt; Session clôturée</p>
        </div>

        <div class="auth-alert auth-alert-success">
            ✓ Votre session CyberLab a été fermée avec succès. Vos cookies de connexion ont été invalidés.
        </div>

        <div style="margin-top: 2rem;">
            <a href="index.php?action=login" class="btn auth-btn-submit" style="text-decoration: none; display: inline-block;">
                SE RECONNECTER
            </a>
        </div>

        <div class="auth-links" style="margin-top: 1.5rem;">
            <a href="index.php">Retour à l'accueil</a>
        </div>
    </div>
</div>
<?php
(new Layout('CyberLab - Déconnexion', ob_get_clean()))->show();
