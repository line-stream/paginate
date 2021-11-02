<?php

namespace QueryBuilders\DBAL;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use LogicException;
use Paginate\Implementers\QueryBuilder as BaseQueryBuilder;

class QueryBuilder extends BaseQueryBuilder
{
    /**
     * Creates a new query builder.
     *
     * This class uses a query map and an existing query, to
     * programmatically add search and sorting statements.
     *
     * @param DBALQueryBuilder $query
     */
    public function __construct(protected DBALQueryBuilder $query)
    {
    }

    /**
     * Get the DBAL query builder instance.
     *
     * @return DBALQueryBuilder
     */
    public function getQuery(): DBALQueryBuilder
    {
        return $this->query;
    }

    /**
     * Set the DBAL query builder.
     *
     * @param DBALQueryBuilder $query
     * @return void
     */
    public function setQuery(mixed $query): void
    {
        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function resetColumns(): DBALQueryBuilder
    {
        return $this->query->resetQueryParts(['select']);
    }

    /**
     * @inheritDoc
     */
    public function count(): DBALQueryBuilder
    {
        return $this->query->select('count(*)');
    }

    /**
     * Executes the DBAL query.
     *
     * @return QueryResult
     * @throws Exception
     */
    public function execute(): QueryResult
    {
        $this->filter()->order();

        return (new QueryResult($this->query->executeQuery()));
    }

    /**
     * @inheritDoc
     */
    protected function filter(): DBALQueryBuilder
    {
        foreach ($this->filters as $key => $filter) {
            $sql = "{$filter['column']} {$filter['condition']} " . $this->query->createNamedParameter(
                    value:       $filter['search_term'],
                    placeHolder: ":filter$key"
                );

            switch ($filter['operation']) {
                case 'AND':
                    $this->query->andWhere($sql);
                    break;

                case 'OR':
                    $this->query->orWhere($sql);
                    break;

                default:
                    $this->query->where($sql);
            }
        }

        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function addFilter(string $value, string $condition, string $subject, ?string $operation): void
    {
        $operation = empty($this->filters) === true ? null : $operation;

        $this->validateFilter($value, $condition, $subject, $operation);

        $this->filters[] = [
            'search_term' => $value,
            'condition' => $condition,
            'column' => $subject,
            'operation' => $operation
        ];
    }

    /**
     * @inheritDoc
     */
    protected function validateFilter(string $searchTern, string $condition, string $column, ?string $operation): void
    {
        if (in_array($condition, ['=', '!=', '>', '<', '>=', '<=' . 'NOT LIKE', 'LIKE']) === false) {
            throw new LogicException("The given filter condition '$condition' is not supported.");
        }

        if (in_array($operation, ['OR', 'AND']) === false && empty($this->filters) === false) {
            throw new LogicException("The given filter operation '$operation' is not supported.");
        }
    }

    /**
     * @inheritDoc
     */
    public function addOrder(string $subject, string $order = 'ASC'): void
    {
        $order = strtoupper($order);

        $this->validateOrderColumn($subject, $order);

        $this->orderColumns[] = compact('subject', 'order');
    }

    /**
     * @inheritDoc
     */
    protected function validateOrderColumn(string $column, string $order): void
    {
        if (in_array($order, ['ASC', 'DESC']) === false) {
            throw new LogicException("Given sort order '$order' is not supported.");
        }
    }

    /**
     * @inheritDoc
     */
    public function setLimit(int $offset, int $noOfResults): DBALQueryBuilder
    {
        return $this->query
            ->setFirstResult($offset)
            ->setMaxResults($noOfResults);
    }

    /**
     * @inheritDoc
     */
    public function removeLimit(): DBALQueryBuilder
    {
        return $this->query
            ->setFirstResult(0)
            ->setMaxResults(null);
    }

    /**
     * @inheritDoc
     */
    protected function order(): DBALQueryBuilder
    {
        foreach ($this->orderColumns as $orderColumn) {
            $this->query->addOrderBy($orderColumn['column'], $orderColumn['order']);
        }

        return $this->query;
    }
}