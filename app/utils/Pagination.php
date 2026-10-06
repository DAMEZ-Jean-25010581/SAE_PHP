<?php

namespace Utils;

/**
 * Pagination réutilisable pour toutes les listes du site.
 */
class Pagination
{
    private int $perPage;
    private int $totalPages;
    private int $currentPage;

    /**
     * @param int   $totalItems    nombre total d'éléments
     * @param int   $perPage       éléments par page
     * @param mixed $requestedPage numéro de page venant de l'URL (non fiable)
     */
    public function __construct(private int $totalItems, int $perPage, mixed $requestedPage = 1)
    {
        $this->perPage = max(1, $perPage);
        $this->totalPages = max(1, (int) ceil($this->totalItems / $this->perPage));

        // Page invalide (abc, -5, 999...) : ramenée entre 1 et la dernière page
        $page = filter_var($requestedPage, FILTER_VALIDATE_INT);
        $this->currentPage = min($this->totalPages, max(1, $page === false ? 1 : $page));
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /** Nombre d'éléments à sauter (OFFSET SQL). */
    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    /**
     * Génère les liens « Précédent 1 2 3 Suivant » (vide s'il n'y a qu'une page).
     *
     * @param string $baseUrl adresse de la page, ex. '/'
     * @param string $anchor  section où revenir après le clic, ex. 'ranking'
     */
    public function render(string $baseUrl, string $anchor = ''): string
    {
        if ($this->totalPages <= 1) {
            return '';
        }

        $suffix = $anchor !== '' ? '#' . $anchor : '';
        $url = static fn (int $page): string =>
            htmlspecialchars($baseUrl . '?page=' . $page . $suffix, ENT_QUOTES, 'UTF-8');

        $html = '<nav class="pagination" aria-label="Pagination">';

        // Précédent : grisé sur la première page pour que les boutons ne bougent pas
        if ($this->currentPage > 1) {
            $html .= '<a class="pagination-link pagination-arrow pagination-prev" href="' . $url($this->currentPage - 1) . '" rel="prev" aria-label="Page précédente">'
                . '<span aria-hidden="true">&lsaquo;</span><span class="pagination-label">Précédent</span></a>';
        } else {
            $html .= '<span class="pagination-link pagination-arrow pagination-prev is-disabled" aria-hidden="true">'
                . '<span>&lsaquo;</span><span class="pagination-label">Précédent</span></span>';
        }

        foreach ($this->getVisiblePages() as $page) {
            if ($page === null) {
                $html .= '<span class="pagination-ellipsis" aria-hidden="true">&hellip;</span>';
            } elseif ($page === $this->currentPage) {
                $html .= '<span class="pagination-link pagination-page is-current" aria-current="page">' . $page . '</span>';
            } else {
                $html .= '<a class="pagination-link pagination-page" href="' . $url($page) . '" aria-label="Page ' . $page . '">' . $page . '</a>';
            }
        }

        // Suivant : grisé sur la dernière page
        if ($this->currentPage < $this->totalPages) {
            $html .= '<a class="pagination-link pagination-arrow pagination-next" href="' . $url($this->currentPage + 1) . '" rel="next" aria-label="Page suivante">'
                . '<span class="pagination-label">Suivant</span><span aria-hidden="true">&rsaquo;</span></a>';
        } else {
            $html .= '<span class="pagination-link pagination-arrow pagination-next is-disabled" aria-hidden="true">'
                . '<span class="pagination-label">Suivant</span><span>&rsaquo;</span></span>';
        }

        // Rappel « page X / Y », affiché seulement sur mobile
        $html .= '<span class="pagination-status">' . $this->currentPage . ' / ' . $this->totalPages . '</span>';

        return $html . '</nav>';
    }

    /**
     * Pages affichées : la première, la dernière et deux autour de la page courante.
     * null correspond à « … ».
     *
     * @return array<int|null>
     */
    private function getVisiblePages(): array
    {
        $pages = [];
        $previous = 0;

        for ($page = 1; $page <= $this->totalPages; $page++) {
            $isNearCurrent = abs($page - $this->currentPage) <= 2;

            if ($page === 1 || $page === $this->totalPages || $isNearCurrent) {
                if ($page - $previous > 1) {
                    $pages[] = null;
                }
                $pages[] = $page;
                $previous = $page;
            }
        }

        return $pages;
    }
}
