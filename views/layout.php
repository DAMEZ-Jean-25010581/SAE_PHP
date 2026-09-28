<?php
namespace SAE_PHP\views;
class Layout {
public function __construct(private string $title, private string $content) {}
public function show(): void {
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="../_assets/images/CyberLab_logo.svg">
    <link rel="stylesheet" href="../_assets/styles/styles.css">
    <title>CyberLab - Accueil</title>
    <meta name="description" content="CyberLab est une plateforme pour apprendre la cybersécurité par la pratique, avec des niveaux progressifs.">

    <!-- FONTS -->
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&family=Audiowide&family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

</head>
<body>

    <!-- NAVIGUATION BAR -->

        <header>
            <nav>
                <ul>

                    <li class="header-links">
                        <ul>
                            <li><a href="/"><img id="logo" src="/_assets/images/CyberLab_logo.svg"></a></li>
                            <li><a class="header-link" href="#levels">NIVEAUX</a></li>
                            <li><a class="header-link" href="#ranking">CLASSEMENT</a></li>
                            <li><a class="header-link" href="/a-propos">A PROPOS</a></li>
                            <li><a class="header-link" href="/contact">CONTACT</a></li>
                        </ul>
                    </li>

                    <li><button id="mode-toggle"><img src="../_assets/images/icons/Light_mode_icon.svg"></button></li>
                    <li>
                        <form action="/authentification">
                            <button class="btn">SE CONNECTER</button>
                        </form>
                    </li>
                    <li>
                        <form action="/inscription">
                            <button class="btn">S'INSCRIRE</button>
                        </form>
                    </li>
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