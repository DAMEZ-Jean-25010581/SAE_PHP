<?php
use SAE_PHP\views\Layout;

ob_start();
?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">NOUVEAU MOT DE PASSE</h1>
            <p class="auth-subtitle">&gt; Mise à jour sécurisée du compte</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                ⚠ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($user !== null): ?>
            <form action="index.php?action=reset_password" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

                <div class="auth-group">
                    <label for="password">NOUVEAU MOT DE PASSE :</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="••••••••••••">
                    <div class="password-hints">
                        <strong>Règles de sécurité :</strong>
                        <ul>
                            <li>12 à 72 caractères</li>
                            <li>Au moins une majuscule et une minuscule</li>
                            <li>Au moins un chiffre et un caractère spécial</li>
                        </ul>
                    </div>
                </div>

                <div class="auth-group">
                    <label for="password_confirm">CONFIRMER LE MOT DE PASSE :</label>
                    <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password" placeholder="••••••••••••">
                </div>

                <button type="submit" class="btn auth-btn-submit">RÉINITIALISER LE MOT DE PASSE</button>
            </form>
        <?php else: ?>
            <div class="auth-links">
                <a href="index.php?action=forgot_password">Demander un nouveau lien</a>
            </div>
        <?php endif; ?>

        <div class="auth-links">
            Retour à la <a href="index.php?action=login">connexion</a>
        </div>
    </div>
</div>
<?php
(new Layout('CyberLab - Nouveau mot de passe', ob_get_clean()))->show();
