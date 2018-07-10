<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter\Rules;

use UnexpectedValueException;

/**
 * Compare two numbers using >, <, >=, <=, = operators.
 */
class NumberCompare extends AbstractNumber implements RuleSanitizeInterface
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'number'];

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @param int|float $received
     * @param string    $operator
     * @param int|float $compare
     *
     * @return bool
     */
    public function validate($received, string $operator, $compare): bool
    {
        if (!is_numeric($received)) {
            return true;
        }

        if (!is_numeric($compare)) {
            return true;
        }

        settype($received, 'float');
        settype($compare, 'float');

        if ($this->switchOperator($operator, $received, $compare)) {
            return false;
        }

        $this->message = "Received number is not {$operator} {$compare}";

        return true;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string    $operator
     * @param int|float $numberReceived
     * @param int|float $numberCompare
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, &$numberReceived, &$numberCompare): bool
    {
        switch ($operator) {
            case '>': //greater than
                return $numberReceived > $numberCompare;
            case '<': //less than
                return $numberReceived < $numberCompare;
            case '>=': //greater than or equal
                return $numberReceived >= $numberCompare;
            case '<=': //less than or equal
                return $numberReceived <= $numberCompare;
            case '=': //equal
                return !($numberReceived - $numberCompare);
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted >, <, >=, <=, =");
        }
    }

    /**
     * Return error message.
     *
     * @return string Error message
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
