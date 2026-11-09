<?php

namespace SAE_PHP\views;

/**
 * Page de synthèse d'un module (#198) : les notions clés de chaque cours,
 * à relire avant l'évaluation finale. Imprimable (voir @media print dans styles.css).
 *
 * Paramètres attendus (fournis par SyntheseController quand la base #187 sera prête) :
 *   - module     : ['id' => int, 'name' => string, 'description' => string]
 *   - syntheses  : liste de ['title' => string, 'content' => string]
 */
class SyntheseView implements ViewInterface
{
    /** @param array<string, mixed> $params */
    public function render(array $params = []): void
    {
        $module = is_array($params['module'] ?? null) ? $params['module'] : [];
        $syntheses = is_array($params['syntheses'] ?? null) ? $params['syntheses'] : [];
        $moduleId = (int) ($module['id'] ?? 0);
        $moduleName = (string) ($module['name'] ?? 'Module');
        $moduleDescription = (string) ($module['description'] ?? '');
        $e = static fn (string $text): string => htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        ?>
<section class="synthesis" aria-labelledby="synthesis-title">
    <h1 id="synthesis-title" class="main-title">SYNTHÈSE</h1>
    <h2 class="subtitle"><?= $e($moduleName) ?></h2>
    <?php if ($moduleDescription !== ''): ?>
        <p class="subsubtitle">// <?= $e($moduleDescription) ?></p>
    <?php endif; ?>

    <?php if ($syntheses === []): ?>
        <p class="synthesis-empty">Aucune synthèse n'est encore disponible pour ce module.</p>
    <?php else: ?>
        <ol class="synthesis-list">
            <?php foreach ($syntheses as $index => $synthesis): ?>
                <li class="synthesis-card">
                    <h3 class="synthesis-card-title">
                        <span class="synthesis-number"><?= sprintf('%02d', (int) $index + 1) ?></span>
                        <?= $e((string) ($synthesis['title'] ?? 'Notion ' . ((int) $index + 1))) ?>
                    </h3>
                    <p class="synthesis-content"><?= nl2br($e((string) ($synthesis['content'] ?? ''))) ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <div class="synthesis-actions">
        <a class="btn" href="index.php?action=modules">RETOUR AUX MODULES</a>
        <button type="button" class="btn" id="print-synthesis">IMPRIMER</button>
        <?php if ($moduleId > 0): ?>
            <a class="btn" href="index.php?action=evaluation&amp;module=<?= $moduleId ?>">PASSER L'ÉVALUATION</a>
        <?php endif; ?>
    </div>
</section>
<script src="/_assets/scripts/print.js"></script>
<?php
    }
}
