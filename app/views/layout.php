<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="CyberLab: AOUAD Ikram,DAMEZ Jean, DJEBIR Yasmine, SAID-BOUTABOUZI Wanis, SEBRI Tasnim">
    <link rel="icon" type="image/svg+xml" href="/_assets/images/logo/CyberLab_logo.svg">
    <link rel="stylesheet" href="/_assets/styles/styles.css">
    <link rel="me" href="https://www.instagram.com/cybe.rlab00">
    <link rel="me" href="https://www.tiktok.com/@cyberlab00">
    <title><?= htmlspecialchars($title ?? 'CyberLab') ?></title>
    <meta name="description" content="CyberLab est une plateforme pour apprendre la cybersécurité par la pratique, avec des niveaux progressifs.">

     <!-- TWITTER META TAGS -->
    
    <meta name="twitter:title" content="placeholderNAME-PAGE">
    <meta name="twitter:description" content="découvrez l'univers de la cybersécurité avec Cyberlab">
    <meta name="twitter:image" content="placeholder">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:domain" content="cyberlab.alwaysdata.net">
    <meta name="twitter:url" content="https://cyberlab.alwaysdata.net/">
    <meta name="twitter:image:alt" content="CyberLab website image">
    <!-- END -->

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&family=Audiowide&family=Chakra+Petch:wght@200;300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- NAVIGATION BAR -->
    <header>
        <input type="checkbox" id="menu-checkbox" class="menu-checkbox" aria-label="Ouvrir le menu">
        <label for="menu-checkbox" class="menu-toggle">
            <span class="menu-toggle-bar"></span>
            <span class="menu-toggle-bar"></span>
            <span class="menu-toggle-bar"></span>
        </label>
        <nav>
            <ul>
                <li class="header-links">
                    <ul>
                        <li><a href="/"><img id="logo" src="/_assets/images/logo/CyberLab_logo.svg" alt="CyberLab Logo"></a></li>
                        <li><a class="header-link" href="/#levels">NIVEAUX</a></li>
                        <li><a class="header-link" href="/#ranking">CLASSEMENT</a></li>
                        <li><a class="header-link" href="/a-propos">A PROPOS</a></li>
                        <li><a class="header-link" href="/contact">CONTACT</a></li>
                    </ul>
                </li>

                <li><button id="mode-toggle"><img src="/_assets/images/icons/Light_mode_icon.svg" alt="Mode"></button></li>
                <?php if (\Utils\SessionHelpers::isLogin()): ?>
                    <li>
                        <span class="user-greeting">
                            <?= htmlspecialchars($_SESSION['user']['username'] ?? $_SESSION['user']['name'] ?? 'Utilisateur'); ?>
                        </span>
                    </li>
                    <li>
                        <a href="/account" class="btn">MON COMPTE</a>
                    </li>
                    <li>
                        <form action="index.php?action=logout" method="post">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn">DÉCONNEXION</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="index.php?action=login" class="btn">SE CONNECTER</a>
                    </li>
                    <li>
                        <a href="index.php?action=register" class="btn">S'INSCRIRE</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <!-- FOOTER -->
    <footer>
        <ul>
            <li><span>© 2026 CyberLab</span></li>
            <li><span><a href="/plan-site">Plan du site</a></span></li>
            <li><span><a href="/mentions-legales">Mentions légales</a></span></li>
        </ul>
    </footer>
</body>
</html>