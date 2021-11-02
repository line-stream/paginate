<?php

namespace Src\Contracts;

use LogicException;

/**
 * Interface for a simple query builder, for query builders.
 *
 * Allowing for simple multi filtering statements, ordering and limiting.
 */
interface QueryBuilder
{
    /**
     * Executes the query builders query.
     *
     * @return QueryResult
     */
    public function execute(): QueryResult;

    /**
     * Get the query builders query.
     *
     * @return mixed
     */
    public function getQuery(): mixed;

    /**
     * Set the query builders query,
     *
     * @param mixed $query
     * @return void
     */
    public function setQuery(mixed $query): void;

    /**
     * Adds a new filter condition to the query.
     *
     * When passing in the first query, the operation can be set to null.
     * Subsequent filters must provide a valid operation (AND, OR).
     *
     * @param string $value Value or term to search/filter with
     * @param string $condition A valid condition (=,!=,<,etc) to use, see filter validation rules
     * @param string $subject The subject/column to filter on
     * @param string|null $operation If multiple filters given, (and,or) an operation must be supplied
     * @return void
     * @throws LogicException
     */
    public function addFilter(string $value, string $condition, string $subject, ?string $operation): void;

    /**
     * Adds order subject/column to query.
     *
     * Order can be ASC or DESC
     *
     * @param string $subject
     * @param string $order
     * @return void
     * @throws LogicException
     */
    public function addOrder(string $subject, string $order = 'ASC'): void;

    /**
     * Removes all set filters.
     *
     * @return void
     */
    public function resetFilters(): void;

    /**
     * Removes all set orders.
     *
     * @return void
     */
    public function resetOrders(): void;

    /**
     * Removes all columns/subjects to be fetched.
     *
     * @return mixed
     */
    public function resetColumns(): mixed;

    /**
     * Set query to count fetched records.
     *
     * @return mixed
     */
    public function count(): mixed;

    /**
     * Sets a limit on results.
     *
     * @param int $offset The starting position
     * @param int $noOfResults The total number of records
     * @return mixed
     */
    public function setLimit(int $offset, int $noOfResults): mixed;

    /**
     * Removes the set limit.
     *
     * @return mixed
     */
    public function removeLimit(): mixed;
}