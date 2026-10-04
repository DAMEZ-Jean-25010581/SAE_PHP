<?php
?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">INSCRIPTION</h1>
            <p class="auth-subtitle">&gt; Rejoindre la communauté CyberLab</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                ⚠ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=register" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="auth-group">
                <label for="login">PSEUDO :</label>
                <input type="text" id="login" name="login" required autocomplete="username" placeholder="Votre pseudo (3 à 30 caractères)" value="<?= htmlspecialchars($_POST['login'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="auth-group">
                <label for="email">ADRESSE EMAIL :</label>
                <input type="email" id="email" name="email" required autocomplete="email" placeholder="votre.email@domaine.com" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="auth-group">
                <label for="password">MOT DE PASSE :</label>
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

            <button type="submit" class="btn auth-btn-submit">S'ENREGISTRER</button>
        </form>

        <div class="auth-links">
            Déjà inscrit ? <a href="index.php?action=login">Se connecter</a>
        </div>
    </div>
</div>
