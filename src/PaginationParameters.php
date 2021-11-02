<?php

namespace Src;

class PaginationParameters
{
    protected int $resultsPerPage;
    protected int $currentPage;
    protected array $sorts = [];
    protected array $filters = [];

    public function __construct()
    {
        $this->setCurrentPage();
        $this->setResultsPerPage();
        $this->setFilters();
        $this->setSorts();
    }

    /**
     * Get the results per page.
     *
     * @return int
     */
    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    /**
     * Sets the results to show per page/
     *
     * The minimum results to be requested per page is 10.
     * The maximum results to be requested per page is 250.
     *
     * @return void
     */
    protected function setResultsPerPage(): void
    {
        $this->resultsPerPage = max($this->resultsPerPage, 10);
        $this->resultsPerPage = min($this->resultsPerPage, 250);
    }

    /**
     * Get the requested page.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Set the current paginated page.
     *
     * @return void
     */
    protected function setCurrentPage(): void
    {
        $this->currentPage = max($this->currentPage, 1);
    }

    /**
     * Get the sortable columns.
     *
     * @return array
     */
    public function getSorts(): array
    {
        return $this->sorts;
    }

    /**
     * Gets the request query for sorts/orders on pagination columns.
     *
     * @return void
     */
    protected function setSorts(): void
    {
    }

    /**
     * Gets the filter rules.
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Gets the request query for filters on pagination columns.
     *
     * @return void
     */
    protected function setFilters(): void
    {
    }
}