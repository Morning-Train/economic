<?php

namespace Morningtrain\Economic\Classes;

use Closure;

class EconomicQueryFilterBuilder
{
    const FILTER_OPERATOR_EQUAL = '$eq:';

    const FILTER_OPERATOR_NOT_EQUAL = '$ne:';

    const FILTER_OPERATOR_GREATER_THAN = '$gt:';

    const FILTER_OPERATOR_GREATER_THAN_OR_EQUAL = '$gte:';

    const FILTER_OPERATOR_LESS_THAN = '$lt:';

    const FILTER_OPERATOR_LESS_THAN_OR_EQUAL = '$lte:';

    const FILTER_OPERATOR_LIKE = '$like:';

    const FILTER_RELATION_AND = '$and:';

    const FILTER_RELATION_OR = '$or:';

    const FILTER_OPERATOR_IN = '$in:';

    const FILTER_OPERATOR_NOT_IN = '$nin:';

    const FILTER_VALUE_NULL = '$null:';

    const ESCAPES = [
        '$' => '$$',
        '(' => '$(',
        ')' => '$)',
        '*' => '$*',
        ',' => '$,',
        '[' => '$[',
        ']' => '$]',
    ];

    protected array $filters = [];

    public function __construct(protected string $relation)
    {
    }

    protected function convertOperator(string $operator): string
    {
        switch ($operator) {
            case '=':
                return static::FILTER_OPERATOR_EQUAL;
            case '!=':
                return static::FILTER_OPERATOR_NOT_EQUAL;
            case '>':
                return static::FILTER_OPERATOR_GREATER_THAN;
            case '>=':
                return static::FILTER_OPERATOR_GREATER_THAN_OR_EQUAL;
            case '<':
                return static::FILTER_OPERATOR_LESS_THAN;
            case '<=':
                return static::FILTER_OPERATOR_LESS_THAN_OR_EQUAL;
            case 'LIKE':
            case 'like':
                return static::FILTER_OPERATOR_LIKE;
            case 'IN':
            case 'in':
                return static::FILTER_OPERATOR_IN;
            case 'NOT IN':
            case 'not in':
                return static::FILTER_OPERATOR_NOT_IN;
        }

        return $operator;
    }

    protected static function isOperator(string $operator): bool
    {
        return in_array($operator, [
            '=',
            '!=',
            '>',
            '>=',
            '<',
            '<=',
            'LIKE',
            'like',
            'IN',
            'in',
            'NOT IN',
            'not in',
            static::FILTER_OPERATOR_EQUAL,
            static::FILTER_OPERATOR_NOT_EQUAL,
            static::FILTER_OPERATOR_GREATER_THAN,
            static::FILTER_OPERATOR_GREATER_THAN_OR_EQUAL,
            static::FILTER_OPERATOR_LESS_THAN,
            static::FILTER_OPERATOR_LESS_THAN_OR_EQUAL,
            static::FILTER_OPERATOR_LIKE,
            static::FILTER_OPERATOR_IN,
            static::FILTER_OPERATOR_NOT_IN,
        ]);
    }

    protected function whereNested(Closure $closure)
    {

        call_user_func($closure, $this);

        return $this;
    }

    public function where(int|string|Closure $propertyName, ?string $operatorOrValue = null, mixed $value = null): static
    {
        if ($propertyName instanceof Closure) {
            return $this->whereNested($propertyName);
        }

        $operator = ! static::isOperator($operatorOrValue) ? static::FILTER_OPERATOR_EQUAL : $operatorOrValue;
        $value = is_null($value) && ! static::isOperator($operatorOrValue) ? $operatorOrValue : $value;

        if (is_string($value)) {
            $value = str_replace(array_keys(static::ESCAPES), array_values(static::ESCAPES), $value);
        }

        if ($value === null) {
            $value = static::FILTER_VALUE_NULL;
        }

        if ($value === true) {
            $value = 'true';
        }

        if ($value === false) {
            $value = 'false';
        }

        $this->filters[] = [
            'property' => $propertyName,
            'operator' => $this->convertOperator($operator),
            'value' => $value,
        ];

        return $this;
    }

    public function orWhere(int|string|Closure $propertyName, ?string $operatorOrValue = null, mixed $value = null): static
    {
        $instance = new static(static::FILTER_RELATION_OR);

        $instance->where($propertyName, $operatorOrValue, $value);

        $this->filters[] = $instance;

        return $this;
    }

    public function buildString(): string
    {
        $strings = [];

        foreach ($this->filters as $filter) {
            if (is_a($filter, static::class)) {
                $strings[] = (string) $filter;

                continue;
            }

            $string = '';

            if (! empty($strings)) {
                $string .= static::FILTER_RELATION_AND;
            }

            $string .= $filter['property'].$filter['operator'].$filter['value'];

            $strings[] = $string;
        }

        return implode('', $strings);
    }

    public function __toString(): string
    {
        return $this->relation.'('.$this->buildString().')';
    }
}
