<?php

namespace QueryBuilders\DBAL;

use Doctrine\DBAL\{Exception, Result};
use Paginate\Implementers\QueryResult as BaseQueryResult;

class QueryResult extends BaseQueryResult
{
    /**
     * @param Result $result
     */
    public function __construct(protected Result $result)
    {
    }

    /**
     * Gets associative results from DBAL results.
     *
     * @return array
     * @throws Exception
     */
    public function getAssociative(): array
    {
        return $this->result->fetchAllAssociative();
    }

    /**
     * Get a single result from DBAL results.
     *
     * @return mixed
     * @throws Exception
     */
    public function getOne(): mixed
    {
        return $this->result->fetchOne();
    }
}