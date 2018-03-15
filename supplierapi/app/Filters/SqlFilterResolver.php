<?php

namespace App\Filters;

use App\Exceptions\FilterResolutionException;

class SqlFilterResolver
{
    private $maps;

    /**
     * ContextMapper constructor.
     * @param ContextMap[] $maps
     */
    public function __construct(array $maps = [])
    {
        $this->maps = [];

        foreach ($maps as $map) {
            $this->maps[] = $map;
        }
    }

    private function indexOf(string $context): int
    {
        $index = -1;

        for ($i = 0; $i < count($this->maps); $i++) {

            if ($this->maps[$i]->getProperty() == $context) {
                $index = $i;
                break;
            }
        }

        return $index;
    }

    public function resolve(Filter $filter): WhereStatement
    {
        $index = $this->indexOf($filter->getProperty());

        if ($index == -1) {
            throw new FilterResolutionException('Invalid property \'' . $filter->getProperty() . '\' given in filter \'' . $filter . '\'');
        }

        $map = $this->maps[$index];

        $column = $map->getColumn();
        $equality = null;

        try {
            $value = $map->convert($filter->getValue());
        } catch (\Exception $e) {
            throw new FilterResolutionException('Unable to resolve value \'' . $filter->getValue() . '\' in filter \'' . $filter . '\'');
        }

        $sql = '';

        switch ($filter->getEquality()) {
            case Equality::EQUAL:

                if (strtolower($value) === 'null') {
                    $equality = 'IS NULL';
                } else {
                    $equality = '=';
                }
                break;
            case Equality::NOT_EQUAL:

                if (strtolower($value) === 'null') {
                    $equality = 'IS NOT NULL';
                } else {
                    $equality = '!=';
                }
                break;
            case Equality::GREATER_THAN:
                $equality = '>';
                break;
            case Equality::LESS_THAN:
                $equality = '<';
                break;
            case Equality::GREATER_THAN_EQUAL:
                $equality = '>=';
                break;
            case Equality::LESS_THAN_EQUAL:
                $equality = '<=';
                break;
            case Equality::LIKE:
                $equality = 'LIKE';
                break;
            case Equality::NOT_LIKE:
                $equality = 'NOT LIKE';
                break;
            default:
                throw new FilterResolutionException('Invalid equality \'' . $equality . '\' given in filter \'' . $filter . '\'');
                break;
        }

        if ($equality === 'LIKE' || $equality === 'NOT LIKE') {
            $sql .= 'LOWER(' . $column . ')';
        } else {
            $sql .= $column;
        }
        $sql .= ' ' . $equality;
        $placeholder = null;

        if ($equality != 'IS NULL' && $equality !== 'IS NOT NULL') {
            $name = 'p' . uniqid();
            $sql .= ' :' . $name;

            if ($equality === 'LIKE' || $equality === 'NOT LIKE') {
                $value = strtolower($value);
            }
            $placeholder = new Placeholder($name, $value);
        }

        return new WhereStatement($sql, $placeholder);
    }
}