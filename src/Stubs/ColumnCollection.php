<?php

namespace Src\Stubs;

abstract class ColumnCollection
{
    /** @var Column[] */
    protected array $columns;

    public function __construct()
    {
    }

    /**
     * Returns the query map.
     *
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Sets the query map.
     *
     * @param Column[] $columns
     * @return void
     */
    public function setColumns(array $columns): void
    {
        foreach ($columns as $key => $column) {
            if ($column instanceof Column === true && is_numeric($key) === false) {
                $this->columns[$key] = $column;
            }
        }
    }

    /**
     * Checks if the column index exists.
     *
     * @param string $index
     * @return bool
     */
    public function exists(string $index): bool
    {
        return isset($this->columns[$index]);
    }

    /**
     * Get a column by its index name.
     *
     * if the index is not known then null is returned.
     *
     * @param string $index
     * @return Column|null
     */
    public function get(string $index): Column|null
    {
        return $this->columns[$index] ?? null;
    }
}