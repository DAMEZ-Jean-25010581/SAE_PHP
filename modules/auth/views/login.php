<?php
use SAE_PHP\views\Layout;

$logoutSuccess = isset($_GET['logout']) && $_GET['logout'] === 'success';
$registeredSuccess = isset($_GET['registered']) && $_GET['registered'] === 'success';
$resetSuccess = isset($_GET['reset']) && $_GET['reset'] === 'success';

ob_start();
?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">CONNEXION</h1>
            <p class="auth-subtitle">&gt; Accéder au terminal CyberLab</p>
        </div>

        <?php if ($logoutSuccess): ?>
            <div class="auth-alert auth-alert-success">
                ✓ Vous avez été déconnecté avec succès.
            </div>
        <?php endif; ?>

        <?php if ($registeredSuccess): ?>
            <div class="auth-alert auth-alert-success">
                ✓ Votre compte a été créé avec succès ! Connectez-vous ci-dessous.
            </div>
        <?php endif; ?>

        <?php if ($resetSuccess): ?>
            <div class="auth-alert auth-alert-success">
                ✓ Mot de passe réinitialisé avec succès ! Vous pouvez vous connecter.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                ⚠ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="auth-group">
                <label for="login">IDENTIFIANT :</label>
                <input type="text" id="login" name="login" required autocomplete="username" placeholder="Entrez votre identifiant" value="<?= htmlspecialchars($_POST['login'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="auth-group">
                <label for="password">MOT DE PASSE :</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••••••">
            </div>

            <div style="text-align: right; margin-top: -8px; margin-bottom: 16px;">
                <a href="index.php?action=forgot_password" style="font-family: 'Chakra Petch', sans-serif; font-size: 0.85rem; color: #48C0D2; text-decoration: none;">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn auth-btn-submit">SE CONNECTER</button>
        </form>

        <div class="auth-links">
            Nouveau sur la plateforme ? <a href="index.php?action=register">S'inscrire</a>
        </div>
    </div>
</div>
<?php
(new Layout('CyberLab - Connexion', ob_get_clean()))->show();
