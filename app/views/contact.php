<?php

$erreurs = [];
$succes = false;
$anciens = [];
$email = $anciens['email'] ?? '';
$objet = $anciens['objet'] ?? '';
$message = $anciens['message'] ?? '';

?>
        <section class="contact">
            <h1 class="main-title">Une question ?</h1>
            <p class="subsubtitle">// Veuillez remplir le formulaire ci-dessous</p>

            <?php if ($succes): ?>
                <p class="succes">Votre message a bien été envoyé.</p>
            <?php endif; ?>

            <?php foreach ($erreurs as $erreur): ?>
                <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
            <?php endforeach; ?>

            <form action="/contact" method="post">

            <div class="auth-group">        
                        <label for="email">Adresse e-mail </label>
                        <input type="email" name="email" id="email"
                               value="<?= htmlspecialchars($email) ?>" required>
                    

                    <label for="objet">Objet
                        <input type="text" name="objet" id="objet"
                               value="<?= htmlspecialchars($objet) ?>" required>
                    </label>
            </div>

                    <label for="message" class="large">Sujet
                        <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>
                    </label>


                <button type="submit">Soumettre</button>
            </form>
        </section>
