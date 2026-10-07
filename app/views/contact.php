<?php

$email = $anciens['email'] ?? '';
$objet = $anciens['objet'] ?? '';
$message = $anciens['message'] ?? '';

?>
        <section class="contact">
            <h1 class="main-title">Contact</h1>
            <h2 class="subtitle">Une question ?</h2>
            <h3 class="subsubtitle">// Veuillez remplir le formulaire ci-dessous</h3>

            <?php if ($succes): ?>
                <p class="succes">Votre message a bien été envoyé.</p>
            <?php endif; ?>

            <?php foreach ($erreurs as $erreur): ?>
                <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
            <?php endforeach; ?>

            <form action="/contact" method="post" class="contact-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" maxlength="100"
                         value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="objet">Objet</label>
                        <input type="text" name="objet" id="objet" maxlength="50"
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
