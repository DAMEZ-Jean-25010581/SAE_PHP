<section class="sitemap" aria-labelledby="sitemap-title">
    <h1 id="sitemap-title" class="main-title">Plan du site</h1>
    <p class="subsubtitle">// Retrouvez rapidement toutes les pages de CyberLab</p>

    <nav class="toc" aria-label="Plan du site">
        <ol class="toc-sections">

            <li class="toc-section">
                <h2>Pages principales</h2>
                <ol class="toc-items">
                    <li>
                        <a href="/">Accueil</a>
                        <ol class="toc-items">
                            <li><a href="/#levels">Niveaux</a></li>
                            <li><a href="/#ranking">Classement</a></li>
                        </ol>
                    </li>
                    <li><a href="/a-propos">À propos</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ol>
            </li>

            <li class="toc-section">
                <h2>Compte utilisateur</h2>
                <ol class="toc-items">
                    <?php if (\Utils\SessionHelpers::isLogin()): ?>
                        <li><a href="/logout">Se déconnecter</a></li>
                    <?php else: ?>
                        <li><a href="/login">Se connecter</a></li>
                        <li><a href="/register">S'inscrire</a></li>
                        <li><a href="/forgot_password">Mot de passe oublié</a></li>
                    <?php endif; ?>
                </ol>
            </li>

            <li class="toc-section">
                <h2>Informations</h2>
                <ol class="toc-items">
                    <li><a href="/mentions-legales">Mentions légales</a></li>
                    <li><a href="/plan-site" >Plan du site</a></li>
                </ol>
            </li>

        </ol>
    </nav>
</section>
