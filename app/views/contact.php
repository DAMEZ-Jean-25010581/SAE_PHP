<?php

$erreurs = [];
$succes = false;
$anciens = [];
$email = $anciens['email'] ?? '';
$objet = $anciens['objet'] ?? '';
$message = $anciens['message'] ?? '';

?>
        <section class="contact">
            <h2 class="subtitle">Une question ?</h2>
            <h3 class="subsubtitle">// Veuillez remplir le formulaire ci-dessous</h3>

            <?php if ($succes): ?>
                <p class="succes">Votre message a bien été envoyé.</p>
            <?php endif; ?>

            <?php foreach ($erreurs as $erreur): ?>
                <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
            <?php endforeach; ?>

            <form action="/contact" method="post" class="contact-form">
                <div class="form-row">
                    <div class="auth-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email"
                         value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="objet">Objet</label>
                        <input type="text" name="objet" id="objet"
                            value="<?= htmlspecialchars($objet) ?>" required>
                    </div>
                </div>


                <div class="form-group">
                    <label for="message">Sujet</label>
                    <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>
                </div>

                <button type="submit" class="btn">Soumettre</button>
            </form>
        </section>
