<?php

namespace Src\Contracts;

interface QueryResult
{
    /**
     * Returns an associative array containing fetched results.
     *
     * @return array
     */
    public function getAssociative(): array;

    /**
     * Returns a single value from fetched results.
     *
     * @return mixed
     */
    public function getOne(): mixed;
}