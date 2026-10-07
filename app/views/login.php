<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">CONNEXION</h1>
            <p class="auth-subtitle">&gt; Accéder au terminal CyberLab</p>
        </div>

        <?php if (!empty($successMessage)): ?>
            <div class="auth-alert auth-alert-success">
                ✓ <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                ⚠ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="auth-group">
                <label for="identifier">PSEUDO OU EMAIL :</label>
                <input type="text" id="identifier" name="identifier" required autocomplete="username" placeholder="Votre pseudo ou votre email" value="<?= htmlspecialchars($identifier ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="auth-group">
                <label for="password">MOT DE PASSE :</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••••••">
            </div>

            <div class="forgot-link-wrapper">
                <a href="/forgot_password" class="forgot-link">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn auth-btn-submit">SE CONNECTER</button>
        </form>

        <div class="auth-links">
            Nouveau sur la plateforme ? <a href="/register">S'inscrire</a>
        </div>
    </div>
</div>
