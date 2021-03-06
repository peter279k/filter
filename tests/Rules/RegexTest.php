<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\Regex;
use PHPUnit\Framework\TestCase;

/**
 * Regex Test
 */
class RegexTest extends TestCase
{
    /**
     * Regex provider.
     *
     * @return array
     */
    public function regexProvider(): array
    {
        return [
            ['Linna', '/^Linna$/', false],
            ['linna', '/^Linna$/', true]
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider regexProvider
     *
     * @param string $value
     * @param string $regex
     * @param bool   $result
     */
    public function testValidate(string $value, string $regex, bool $result): void
    {
        $this->assertSame($result, (new Regex())->validate($value, $regex));
    }
}
