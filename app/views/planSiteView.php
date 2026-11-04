<?php

namespace SAE_PHP\views;

/**
 * Plan du site (PlanSiteController).
 */
class PlanSiteView implements ViewInterface
{
    /** @param array<string, mixed> $params */
    public function render(array $params = []): void
    {
        ?>
<section class="sitemap" aria-labelledby="sitemap-title">
    
    <h1 id="sitemap-title" class="subtitle">Plan du site</h1>
    <h2 class="subsubtitle">// Retrouvez rapidement toutes les pages de CyberLab</h2>

    <nav class="toc" aria-label="Plan du site">
        <ol class="toc-sections">

            <li class="toc-section">
                <h2 class="subtitle">Pages principales</h2>
                <ol class="toc-items">
                    <li>
                        <a href="/">Accueil</a>
                        <ol class="toc-items">
                            <li><a href="/#levels">Niveaux</a></li>
                            <li><a href="/#ranking">Classement</a></li>
                        </ol>
                    </li>
                    <li><a href="index.php?action=modules">Modules</a></li>
                    <li><a href="index.php?action=a-propos">À propos</a></li>
                    <li><a href="index.php?action=contact">Contact</a></li>
                </ol>
            </li>

            <li class="toc-section">
                <h2>Compte utilisateur</h2>
                <ol class="toc-items">
                    <?php if (\Utils\SessionHelpers::isLogin()): ?>
                        <li><a href="index.php?action=account">Mon compte</a></li>
                        <li>
                            <form action="/index.php?action=logout" method="post" class="sitemap-logout-form">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit">Se déconnecter</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li><a href="index.php?action=login">Se connecter</a></li>
                        <li><a href="index.php?action=register">S'inscrire</a></li>
                        <li><a href="index.php?action=forgot_password">Mot de passe oublié</a></li>
                    <?php endif; ?>
                </ol>
            </li>

            <li class="toc-section">
                <h2>Informations</h2>
                <ol class="toc-items">
                    <li><a href="index.php?action=mentions-legales">Mentions légales</a></li>
                    <li><a href="index.php?action=plan-site" >Plan du site</a></li>
                </ol>
            </li>

        </ol>
    </nav>
</section>
<?php
    }
}
