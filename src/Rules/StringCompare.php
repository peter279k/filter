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
 * Compare two strings using >, <, >=, <=, =, != operators.
 */
class StringCompare extends AbstractString implements RuleSanitizeInterface
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'string'];

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @param string $received
     * @param string $operator
     * @param string $compare
     *
     * @return bool
     */
    public function validate(string $received, string $operator, string $compare): bool
    {
        if ($this->switchOperator($operator, $received, $compare)) {
            return false;
        }

        return true;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string $operator
     * @param string $strReceived
     * @param string $strCompare
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, string &$strReceived, string &$strCompare): bool
    {
        switch ($operator) {
            case '>': //greater than
                return $strReceived > $strCompare;
            case '<': //less than
                return $strReceived < $strCompare;
            case '>=': //greater than or equal
                return $strReceived >= $strCompare;
            case '<=': //less than or equal
                return $strReceived <= $strCompare;
            case '=': //equal
                return $strReceived === $strCompare;
            case '!=': //not equal
                return $strReceived !== $strCompare;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted >, <, >=, <=, =, !=");
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
