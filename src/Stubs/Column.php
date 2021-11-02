<?php

namespace Paginate\Stubs;

abstract class Column
{
    /**
     * Create a new column instance.
     *
     * @param string $label Human read-able label for the column
     * @param string $raw The column name (could include an alias)
     * @param bool $filterable Can the column be filtered
     * @param bool $sortable Can the column be ordered/sorted
     */
    public function __construct(
        protected string $label,
        protected string $raw,
        protected bool $filterable,
        protected bool $sortable
    ) {
    }

    /**
     * Get a human-readable label for the column.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get the raw representation for the column.
     *
     * @return string
     */
    public function toSql(): string
    {
        return $this->raw;
    }

    /**
     * Is the column is fillable.
     *
     * @return bool
     */
    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    /**
     * Is the column is sortable.
     *
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }
}