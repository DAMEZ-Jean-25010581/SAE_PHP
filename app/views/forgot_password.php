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

        <form action="index.php?action=forgot_password" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="auth-group">
                <label for="identifier">PSEUDO OU EMAIL :</label>
                <input type="text" id="identifier" name="identifier" required placeholder="Entrez votre pseudo ou votre email" value="<?= htmlspecialchars($_POST['identifier'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <button type="submit" class="btn auth-btn-submit">ENVOYER LE LIEN</button>
        </form>

        <div class="auth-links">
            Retour à la <a href="index.php?action=login">connexion</a>
        </div>
    </div>
</div>
