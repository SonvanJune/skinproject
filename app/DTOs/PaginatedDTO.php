<?php

namespace App\DTOs;

/**
 * Data Transfer Object for paginated results.
 */
class PaginatedDTO
{
    /**
     * @var array The data items for the current page.
     */
    public array $data;

    /**
     * @var int The current page number.
     */
    public int $current_page;

    /**
     * @var int The number of items per page.
     */
    public int $per_page;

    /**
     * @var int The total number of items.
     */
    public int $total;

    /**
     * @var int The total number of items.
     */
    public int $totalArr;

    /**
     * @var int The total number of pages.
     */
    public int $last_page;

    /**
     * @var string The key to search data.
     */
    public string $key;


    /**
     * Constructs a new PaginatedDTO instance.
     *
     * @param array $data The data items for the current page.
     * @param int $current_page The current page number.
     * @param int $per_page The number of items per page.
     * @param int $total The total number of items.
     * @throws \InvalidArgumentException if $per_page <= 0 or $total < 0.
     */
    public function __construct(array $data, int $current_page, int $per_page, int $total, string $key = "")
    {
        $this->totalArr = $total;
        if ($current_page == ceil($total / $per_page)) {
            $remainingProducts = $total % $per_page;
            $productsOnCurrentPage = $remainingProducts ? $remainingProducts : $per_page;
        } else {
            $productsOnCurrentPage = $per_page;
        }
        $this->data = $data;
        $this->current_page = $current_page;
        $this->per_page = $per_page;
        $this->total = $productsOnCurrentPage;
        $this->last_page = ceil($total / $per_page);
        $this->key = $key;
    }

    /**
     * Creates a PaginatedDTO instance from the given data.
     *
     * @param array $data The data items for the current page.
     * @param int $current_page The current page number.
     * @param int $per_page The number of items per page.
     * @param int $total The total number of items.
     * @return self A new instance of PaginatedDTO.
     */
    public static function fromData(array $data, int $current_page, int $per_page, int $total, string $key = ""): self
    {
        return new self($data, $current_page, $per_page, $total, $key);
    }
}
