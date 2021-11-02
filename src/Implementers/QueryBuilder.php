<?php

namespace Paginate\Implementers;

use LogicException;
use Paginate\Contracts\QueryBuilder as QueryBuilderContract;

abstract class QueryBuilder implements QueryBuilderContract
{
    protected array $filters = [];
    protected array $orderColumns = [];

    /**
     * @inheritDoc
     */
    public function resetFilters(): void
    {
        $this->filters = [];
    }

    /**
     * @inheritDoc
     */
    public function resetOrders(): void
    {
        $this->orderColumns = [];
    }

    /**
     * Build the filter statements for the query.
     *
     * @return mixed
     */
    abstract protected function filter(): mixed;

    /**
     * Orders (sorts) results by column or columns.
     *
     * @return mixed
     */
    abstract protected function order(): mixed;

    /**
     * Validates if a column can be ordered on.
     *
     * @param string $column
     * @param string $order
     * @return void
     * @throws LogicException If validation fails
     */
    abstract protected function validateOrderColumn(string $column, string $order): void;

    /**
     * Validates if a column can be filtered on.
     *
     * @param string $searchTern
     * @param string $condition
     * @param string $column
     * @param string|null $operation
     * @return void
     * @throws LogicException
     */
    abstract protected function validateFilter(
        string $searchTern,
        string $condition,
        string $column,
        ?string $operation
    ): void;
}