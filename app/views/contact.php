<?php
namespace SAE_PHP\views;
class Contact {
    public function __construct(
        private array $erreurs = [],
        private bool $succes = false,
        private array $anciens = []
    ) {}

    public function show(): void
    {
        $email   = $this->anciens['email'] ?? '';
        $objet   = $this->anciens['objet'] ?? '';
        $message = $this->anciens['message'] ?? '';

        ob_start();
        ?>
        <section class="contact">
            <h1 class="main-title">Une question ?</h1>
            <p class="sous-titre">// Veuillez remplir le formulaire ci-dessous</p>

            <?php if ($this->succes): ?>
                <p class="succes">Votre message a bien été envoyé.</p>
            <?php endif; ?>

            <?php foreach ($this->erreurs as $erreur): ?>
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
                </fieldset>

                <button type="submit">Soumettre</button>
            </form>
        </section>
        <?php
        (new \SAE_PHP\views\Layout('CyberLab - Contact', ob_get_clean()))->show();
    }
} 

      
