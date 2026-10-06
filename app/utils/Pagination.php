<?php

namespace Utils;

/**
 * Reusable pagination helper for every list displayed on the website.
 *
 * Usage in a controller:
 *   $pagination = new Pagination($repository->countAll(), 10, $_GET['page'] ?? 1);
 *   $items = $repository->findPage($pagination->getPerPage(), $pagination->getOffset());
 *
 * Usage in a view:
 *   <?= $pagination->render('/', 'ranking') ?>
 */
class Pagination
{
    private int $perPage;
    private int $totalPages;
    private int $currentPage;

    /**
     * @param int   $totalItems  total number of items (SELECT COUNT(*))
     * @param int   $perPage     number of items per page
     * @param mixed $requestedPage page number taken from the URL (untrusted input)
     */
    public function __construct(private int $totalItems, int $perPage, mixed $requestedPage = 1)
    {
        $this->perPage = max(1, $perPage);
        $this->totalPages = max(1, (int) ceil($this->totalItems / $this->perPage));

        // ?page=abc, ?page=-5 or ?page=999 are clamped between 1 and the last page
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

    /** Number of items to skip (SQL OFFSET). */
    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    /**
     * Builds the "Previous 1 2 3 Next" navigation (empty string when there is a single page).
     *
     * @param string $baseUrl page URL, e.g. '/'
     * @param string $anchor  id of the section to scroll back to, e.g. 'ranking'
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
     * Pages to display: the first, the last and two around the current page.
     * A null value stands for an ellipsis ("…").
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
