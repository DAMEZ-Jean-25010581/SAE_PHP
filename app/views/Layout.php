<?php
namespace SAE_PHP\views;

class Layout
{
    public function __construct(private string $title, private string $content) {}

    public function show(): void
    {
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="/_assets/images/logo/CyberLab_logo.svg">
    <link rel="stylesheet" href="/_assets/styles/styles.css">
    <title><?= htmlspecialchars($this->title); ?></title>
    <meta name="description" content="CyberLab est une plateforme pour apprendre la cybersécurité par la pratique, avec des niveaux progressifs.">

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&family=Audiowide&family=Chakra+Petch:wght@200;300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- NAVIGATION BAR -->
    <header>
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
                <?php if (isset($_SESSION['user'])): ?>
                    <li>
                        <span style="color: #60a5fa; font-weight: bold; margin-right: 8px;">
                            👤 <?= htmlspecialchars($_SESSION['user']['username'] ?? $_SESSION['user']['name'] ?? 'Utilisateur'); ?>
                        </span>
                    </li>
                    <li>
                        <a href="/deconnexion" class="btn" style="text-decoration: none; display: inline-block;">DÉCONNEXION</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/login" class="btn" style="text-decoration: none; display: inline-block;">SE CONNECTER</a>
                    </li>
                    <li>
                        <a href="/register" class="btn" style="text-decoration: none; display: inline-block;">S'INSCRIRE</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
        
    <main>
<?= $this->content; ?>
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
<?php
    }
}