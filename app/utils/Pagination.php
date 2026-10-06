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

        if ($this->currentPage > 1) {
            $html .= '<a class="pagination-link" href="' . $url($this->currentPage - 1) . '" rel="prev">&lt; Précédent</a>';
        }

        foreach ($this->getVisiblePages() as $page) {
            if ($page === null) {
                $html .= '<span class="pagination-ellipsis">…</span>';
            } elseif ($page === $this->currentPage) {
                $html .= '<span class="pagination-link is-current" aria-current="page">' . $page . '</span>';
            } else {
                $html .= '<a class="pagination-link" href="' . $url($page) . '">' . $page . '</a>';
            }
        }

        if ($this->currentPage < $this->totalPages) {
            $html .= '<a class="pagination-link" href="' . $url($this->currentPage + 1) . '" rel="next">Suivant &gt;</a>';
        }

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
