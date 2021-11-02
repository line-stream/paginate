<?php

namespace Paginate\Stubs;

abstract class PaginationColumns extends ColumnCollection
{
    /**
     * Gets all known sortable columns.
     *
     * @return array
     */
    public function getSortableColumns(): array
    {
        $sortable = [];

        foreach ($this->columns as $key => $column) {
            if ($column->isSortable() === true) {
                $sortable[$key] = $column->getLabel();
            }
        }

        return $sortable;
    }

    /**
     * Gets all known filterable columns.
     *
     * @return array
     */
    public function getFilterableColumns(): array
    {
        $filterable = [];

        foreach ($this->columns as $key => $column) {
            if ($column->isFilterable() === true) {
                $filterable[$key] = $column->getLabel();
            }
        }

        return $filterable;
    }
}