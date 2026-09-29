<?php
namespace SAE_PHP\views;
class Homepage { 
    public function show(): void { 
        ob_start();
        ?><!-- UPPER SECTION -->

        <section>
            <h1 class="main-title">CYBERLAB</h1>
        
            <h2 class="subtitle">
                <p class="cursor">
                    <span class="blue-word">> Pirater</span>
                    <span>pour mieux </span>
                    <span class="green-word">protéger. </span>
                </p>
            </h2>

            <h3>
                <p class="description">
                    <span>L’outil pédagogique pour mettre en pratique ses connaissances en </span>
                    <span class="green-word">cybersécurité.</span><br>
                    <span>Découvrez comment les </span>
                    <span class="blue-word">attaques </span>
                    <span>surviennent.</span><br>
                </p>
            </h3>
                
            <li>
                <form action="/inscription">
                    <button class="btn">NOUS REJOINDRE</button>
                </form>
            </li>
            
        </section>

        <!-- STATS LIST -->

        <section class="stats">
            <section class="rectangle-list">
                <ul>

                    <li>
                        <section class="data-rectangle">
                            <span class="rectangle-text">XXX+</span>
                            <p class="data-descriptive-text">UTILISATEURS <br> INSCRITS</p>
                        </section>
                    </li>

                    <li>
                        <section class="data-rectangle">
                            <span class="rectangle-text">XXX+</span>
                            <p class="data-descriptive-text">LEÇONS <br> COMPLÉTÉES</p>
                        </section>
                    </li>

                </ul>
            </section>
        </section>

        <!-- LEVELS -->

        <section id="levels">
            <h2 class="subtitle">
                Niveaux
                <h3 class="subsubtitle">
                    // Apprenez le piratage en le pratiquant
                </h3>
            </h2>
        

            <!-- LEVELS LIST -->
        
            <section class="rectangle-list">
                <ul>
                    <li class="level-rectangle">
                        <span class="rectangle-text">Injection SQL</span>
                        <p>Découvrez comment injecter du code SQL malveillant pour manipuler ou extraire les données d'une base de données</p>
                    </li>
                    <li class="level-rectangle">
                        <span class="rectangle-text">IDOR</span>
                        <p>Modifiez un identifiant dans une requête pour accéder aux données d'un autre utilisateur sans autorisation</p>
                    </li>
                    <li class="level-rectangle">
                        <span class="rectangle-text">Fuite de données</span>
                        <p>Exploitez un chiffrement faible ou absent pour intercepter des mots de passe ou données confidentielles</p>
                    </li>
                </ul>
                <a href="">[liste]</a>
            </section>
        </section>

        <!-- RANKING -->

        <section id="ranking">
            <h2 class="subtitle">
                Classement
                <h3 class="subsubtitle">
                    // Devenez le meilleur CyberLab hacker !
                </h3>
            </h2>
        
            <!-- RANKING LIST -->

            <section class="rectangle-list">
                <ul>
                    <li class="ranking-rectangle">
                        <span class="rectangle-text">Username1</span><br>
                        <p>X points - Progression totale : X%</p>
                        <span class="rank">#1</span>
                    </li>
                    <li class="ranking-rectangle">
                        <span class="rectangle-text">Username2</span><br>
                        <p>X points - Progression totale : X%</p>
                        <span class="rank">#2</span>
                    </li>
                    <li class="ranking-rectangle">
                        <span class="rectangle-text">Username3</span><br>
                        <p>X points - Progression totale : X%</p>
                        <span class="rank">#3</span>
                    </li>
                </ul>
                <a href="">[liste]</a>
            </section>
        </section><?php
        (new \SAE_PHP\views\Layout('CyberLab - Accueil', ob_get_clean()))->show();
    }
}