<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">MON COMPTE</h1>
            <p class="auth-subtitle">&gt; Gérer votre profil CyberLab</p>
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

        <section>
            <div class="auth-header">
                <h2 class="auth-subtitle">&gt; MES INFORMATIONS</h2>
            </div>
            <div class="password-hints">
                <ul>
                    <li>Pseudo : <?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?></li>
                    <li>Email : <?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8') ?></li>
                    <li>Points : <?= $user->getPoints() ?></li>
                    <li>Progression : <?= htmlspecialchars(number_format($user->getProgression(), 2, ',', ' '), ENT_QUOTES, 'UTF-8') ?> %</li>
                </ul>
            </div>
        </section>

        <section class="mt-2">
            <div class="auth-header">
                <h2 class="auth-subtitle">&gt; MODIFIER MON PSEUDO</h2>
            </div>
            <form action="/account" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form" value="username">

                <div class="auth-group">
                    <label for="username">NOUVEAU PSEUDO :</label>
                    <input type="text" id="username" name="username" required minlength="3" maxlength="30" pattern="[a-zA-Z0-9_.\-]{3,30}" autocomplete="username" placeholder="Votre pseudo (3 à 30 caractères)">
                </div>

                <button type="submit" class="btn auth-btn-submit">MODIFIER LE PSEUDO</button>
            </form>
        </section>

        <section class="mt-2">
            <div class="auth-header">
                <h2 class="auth-subtitle">&gt; MODIFIER MON EMAIL</h2>
            </div>
            <form action="/account" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form" value="email">

                <div class="auth-group">
                    <label for="email">NOUVELLE ADRESSE EMAIL :</label>
                    <input type="email" id="email" name="email" required maxlength="100" autocomplete="email" placeholder="votre.email@domaine.com">
                </div>

                <div class="auth-group">
                    <label for="email_current_password">MOT DE PASSE ACTUEL :</label>
                    <input type="password" id="email_current_password" name="current_password" required autocomplete="current-password" placeholder="••••••••••••">
                </div>

                <button type="submit" class="btn auth-btn-submit">MODIFIER L'EMAIL</button>
            </form>
        </section>

        <section class="mt-2">
            <div class="auth-header">
                <h2 class="auth-subtitle">&gt; MODIFIER MON MOT DE PASSE</h2>
            </div>
            <form action="/account" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form" value="password">

                <div class="auth-group">
                    <label for="password_current_password">MOT DE PASSE ACTUEL :</label>
                    <input type="password" id="password_current_password" name="current_password" required autocomplete="current-password" placeholder="••••••••••••">
                </div>

                <div class="auth-group">
                    <label for="password">NOUVEAU MOT DE PASSE :</label>
                    <input type="password" id="password" name="password" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="••••••••••••">
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
                    <label for="password_confirm">CONFIRMER LE NOUVEAU MOT DE PASSE :</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="••••••••••••">
                </div>

                <button type="submit" class="btn auth-btn-submit">MODIFIER LE MOT DE PASSE</button>
            </form>
        </section>

        <section class="mt-2">
            <div class="auth-header">
                <h2 class="auth-subtitle">&gt; SUPPRIMER MON COMPTE</h2>
            </div>
            <p class="auth-alert auth-alert-info">
                Cette action est définitive : votre compte, vos points et votre progression seront supprimés.
            </p>
            <form action="/account" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form" value="delete">

                <div class="auth-group">
                    <label for="delete_current_password">MOT DE PASSE ACTUEL :</label>
                    <input type="password" id="delete_current_password" name="current_password" required autocomplete="current-password" placeholder="••••••••••••">
                </div>

                <button type="submit" class="btn auth-btn-submit">SUPPRIMER DÉFINITIVEMENT MON COMPTE</button>
            </form>
        </section>
    </div>
</div>
