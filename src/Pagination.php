<?php

namespace src;

use Doctrine\DBAL\Exception;
use LogicException;
use Src\Implementers\QueryBuilder;
use Src\Stubs\{Column, PaginationColumns};
use Throwable;

class Pagination
{
    protected int $totalRecords;
    protected array $data;

    /**
     * Create a new pagination instance.
     *
     * @param QueryBuilder $query The target query builder
     * @param array $headers The headers/titles for each column
     */
    public function __construct(
        protected QueryBuilder $query,
        protected PaginationParameters $parameters = (new PaginationParameters),
        protected ?PaginationColumns $columnsMap = null,
        protected array $headers = []
    ) {
    }

    /**
     * Returns the paginated query, with information on pages.
     *
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function paginate(): array
    {
        $this->fetchRecords();
        $this->fetchTotalRecordCount();
        $this->setHeaders();

        return [
            'data' => $this->data,
            'total_records' => $this->totalRecords,
            'current_page' => $this->parameters->getCurrentPage(),
            'items_per_page' => $this->parameters->getResultsPerPage(),
            'last_page' => ceil($this->totalRecords / $this->parameters->getResultsPerPage()),
            'headers' => $this->headers,
        ];
    }

    /**
     * Fetch the limited results for the current page.
     *
     * @return void
     * @throws Throwable
     */
    protected final function fetchRecords()
    {
        $this->query->setLimit(
            (($this->parameters->getCurrentPage() - 1) * $this->parameters->getResultsPerPage()),
            $this->parameters->getResultsPerPage()
        );

        $this->data = $this->query->execute()->getAssociative();
    }

    /**
     * Fetch thw total number of results before limits.
     *
     * Uses MySQL found rows function to get count of records
     * that would have been returned before the limit.
     *
     * @return void
     * @throws Throwable
     */
    protected final function fetchTotalRecordCount(): void
    {
        $query = $this->query;
        $query->resetColumns()
            ->count()
            ->resetOrders()
            ->removeLimit();

        $this->totalRecords = $this->query->execute()->getOne();
    }

    /**
     * Sets the human-readable headers.
     *
     * Set generated headers from the pagination dataset
     * column titles, if no headers have been set.
     *
     * @return void
     */
    protected function setHeaders(): void
    {
        if (empty($this->headers) === false || empty($this->data) === true) {
            return;
        }

        $headers = array_keys(reset($this->data));

        array_walk($headers, fn(&$header) => $header = ucwords(str_replace("_", " ", $header)));

        $this->headers = $headers;
    }

    /**
     * Adds a new filter condition to the query.
     *
     * When passing in the first query, the operation cam be set to null.
     * Subsequent filters must provide a valid operation.
     *
     * @param string $searchTern
     * @param string $condition
     * @param string $column
     * @param string|null $operation
     * @return void
     * @throws LogicException
     */
    public function addFilter(string $searchTern, string $condition, string $column, ?string $operation): void
    {
        if (empty($this->columnsMap) === true) {
            $this->query->addFilter($searchTern, $condition, $column, $operation);
            return;
        }

        if ($this->getColumn($column)->isFilterable() === false) {
            throw new LogicException("The given sort column '$column' is not sortable.");
        }

        $this->query->addFilter($searchTern, $condition, $this->getColumn($column)->toSql(), $operation);
    }

    /**
     * Fetch a given column from pagination column map.
     *
     * @param string $column
     * @return Column
     * @throws LogicException
     */
    protected function getColumn(string $column): Column
    {
        if ($this->columnsMap->get($column) === null) {
            throw new LogicException("The given column name '$column' is not known.");
        }

        return $this->columnsMap->get($column);
    }

    /**
     * Adds order column to query.
     *
     * @param string $column
     * @param string $order
     * @return void
     * @throws LogicException
     */
    public function addOrder(string $column, string $order = 'ASC'): void
    {
        if (empty($this->columnsMap) === true) {
            $this->query->addOrder($column, $order);
            return;
        }

        if ($this->getColumn($column)->isSortable() === false) {
            throw new LogicException("The given sort column '$column' is not sortable.");
        }

        $this->query->addOrder($this->getColumn($column)->toSql(), $order);
    }

    /**
     * Get the current pagination request.
     *
     * @return PaginationParameters
     */
    public function getRequest(): PaginationParameters
    {
        return $this->parameters;
    }

    /**
     * Get the pagination columns map if set.
     *
     * @return PaginationColumns|null
     */
    public function getColumnsMap(): PaginationColumns|null
    {
        return $this->columnsMap;
    }
}