<?php

namespace SAE_PHP\views;

/**
 * Liste des modules de cours (ModulesController).
 */
class ModulesView implements ViewInterface
{
    /** @param array<string, mixed> $params */
    public function render(array $params = []): void
    {
        $modules = $params['modules'] ?? [];
        $levels = $params['levels'] ?? [];
        ?>
<section class="modules" aria-labelledby="modules-title">
    <h1 id="modules-title" class="subtitle">Modules</h1>
    <p class="subsubtitle">// Choisissez une faille et apprenez à l'exploiter</p>

    <?php if (empty($modules)): ?>
        <p class="pagination-info">Aucun module pour le moment.</p>
    <?php else: ?>
        <ul class="module-grid">
            <?php foreach ($modules as $module): ?>
                <li class="module-card">
                    <span class="module-tag" aria-hidden="true"><?= htmlspecialchars($module['tag'], ENT_QUOTES, 'UTF-8') ?></span>
                    <h2 class="rectangle-text module-title"><?= htmlspecialchars($module['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p class="module-description"><?= htmlspecialchars($module['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="module-level module-level-<?= (int) $module['level'] ?>">
                        <span class="module-bars" aria-hidden="true">
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                                <span class="module-bar<?= $i <= $module['level'] ? ' is-on' : '' ?>"></span>
                            <?php endfor; ?>
                        </span>
                        <?= htmlspecialchars($levels[$module['level']] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php
    }
}
