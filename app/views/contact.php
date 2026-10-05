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

                    <label>Adresse e-mail
                        <input type="email" name="email"
                               value="<?= htmlspecialchars($email) ?>" required>
                    </label>

                    <label>Objet
                        <input type="text" name="objet"
                               value="<?= htmlspecialchars($objet) ?>" required>
                    </label>

                    <label class="large">Sujet
                        <textarea name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>
                    </label>


                <button type="submit">Soumettre</button>
            </form>
        </section>
