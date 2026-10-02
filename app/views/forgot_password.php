<?php
use SAE_PHP\views\Layout;

ob_start();
?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">RÉCUPÉRATION</h1>
            <p class="auth-subtitle">&gt; Réinitialiser votre mot de passe</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                ⚠ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="auth-alert auth-alert-success">
                ✓ <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($resetLink)): ?>
            <div class="auth-alert auth-alert-info">
                <strong>[Mode Démo / Évaluation]</strong><br>
                Lien de réinitialisation généré (valide 15 min) :<br>
                <a href="<?= htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') ?>" style="word-break: break-all; color: #5FF6AD; font-size: 0.85rem;">
                    <?= htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        <?php endif; ?>

        <form action="index.php?action=forgot_password" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="auth-group">
                <label for="identifier">IDENTIFIANT OU EMAIL :</label>
                <input type="text" id="identifier" name="identifier" required placeholder="Entrez votre identifiant ou email" value="<?= htmlspecialchars($_POST['identifier'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <button type="submit" class="btn auth-btn-submit">GÉNÉRER LE LIEN</button>
        </form>

        <div class="auth-links">
            Retour à la <a href="index.php?action=login">connexion</a>
        </div>
    </div>
</div>
<?php
(new Layout('CyberLab - Mot de passe oublié', ob_get_clean()))->show();
