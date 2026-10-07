<!-- UPPER SECTION -->
<section>
    <h1 class="main-title">CYBERLAB</h1>

    <h2 class="subtitle">
        <span class="cursor">
            <span class="blue-word">&gt; Pirater</span>
            <span>pour mieux </span>
            <span class="green-word">protéger. </span>
        </span>
    </h2>

    <h3>
        <span class="description">
            <span>L'outil pédagogique pour mettre en pratique ses connaissances en </span>
            <span class="green-word">cybersécurité.</span><br>
            <span>Découvrez comment les </span>
            <span class="blue-word">attaques </span>
            <span>surviennent.</span><br>
        </span>
    </h3>

    <?php if (empty($isLoggedIn)): ?>
        <a href="index.php?action=register" class="btn">NOUS REJOINDRE</a>
    <?php endif; ?>
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
    <h2 class="subtitle">Niveaux</h2>
    <h3 class="subsubtitle">// Apprenez le piratage en le pratiquant</h3>

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
    </section>
</section>

<!-- RANKING -->
<section id="ranking">
    <h2 class="subtitle">Classement</h2>
    <h3 class="subsubtitle">// Devenez le meilleur CyberLab hacker !</h3>

    <!-- RANKING LIST -->
    <section class="rectangle-list">
        <?php if (empty($players)): ?>
            <p class="pagination-info">Aucun joueur pour le moment.</p>
        <?php else: ?>
            <?php $rank = $pagination->getOffset(); ?>
            <ul>
                <?php foreach ($players as $player): $rank++; ?>
                    <li class="ranking-rectangle">
                        <span class="rectangle-text"><?= htmlspecialchars($player->getUsername(), ENT_QUOTES, 'UTF-8') ?></span><br>
                        <p><?= $player->getPoints() ?> points - Progression totale : <?= rtrim(rtrim(number_format($player->getProgression(), 2, ',', ''), '0'), ',') ?>%</p>
                        <span class="rank">#<?= $rank ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (isset($pagination)): ?>
            <?= $pagination->render('/', 'ranking') ?>
        <?php endif; ?>
    </section>
</section>