<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Filter;
use PHPUnit\Framework\TestCase;

/**
 * Filter Test
 */
class FilterTest extends TestCase
{
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function rulesDataResultProvider() : array
    {
        return [
          [['age min 18'],['age' => '19'],0],
          [['age min 18'],['age' => '18'],0],
          [['age min 18'],['agge' => '18'],1], //testing missing field
          [['age min 18'],['age' => '17'],1],
          [['age max 18'],['age' => '19'],1],
          [['age max 18'],['age' => '18'],0],
          [['age max 18'],['age' => '17'],0],
          [['age between 18 20'],['age' => '17'],1],
          [['age between 18 20'],['age' => '18'],0],
          [['age between 18 20'],['age' => '19'],0],
          [['age between 18 20'],['age' => '20'],0],
          [['age between 18 20'],['age' => '21'],1],
          [['password minlength 4'],['password' => 'pas'],1],
          [['password minlength 4'],['password' => 'pass'],0],
          [['password minlength 4'],['password' => 'passw'],0],
          [['password maxlength 4'],['password' => 'pas'],0],
          [['password maxlength 4'],['password' => 'pass'],0],
          [['password maxlength 4'],['password' => 'passw'],1],
          [['email email'],['email' => 'foo@baz.com'],0],
          [['email email'],['email' => 'foobaz.com'],1],
          [['email email'],['email' => 'foo@bazcom'],1],
          [['email email'],['email' => 'foobazcom'],1],
          [['email required email'],['email' => ''],2],
          [['email required email'],['email' => 'foobazcom'],1],
          [['email required email'],['email' => 'foo@baz.com'],0],
        ];
    }
    
    /**
     * Test Filter.
     *
     * @dataProvider rulesDataResultProvider
     * @param array $rule
     * @param array $data
     * @param int $error
     */
    public function testFilter(array $rule, array $data, int $error)
    {
        $filter = new Filter($rule, $data);
        
        $this->assertEquals($error, $filter->getErrors());
    }
    
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function rulesNumberFloatProvider() : array
    {
        return [
          [['price number min 18.5'],['price' => '19.5'],['price' => 19.5],0],
          [['price number min 18.5'],['price' => '18.5'],['price' => 18.5],0],
          [['price number min 18.5'],['price' => '17.5'],['price' => 17.5],1],
          [['price number max 18.5'],['price' => '19.5'],['price' => 19.5],1],
          [['price number max 18.5'],['price' => '18.5'],['price' => 18.5],0],
          [['price number max 18.5'],['price' => '17.5'],['price' => 17.5],0]
        ];
    }
    
    /**
     * Test filter number.
     *
     * @dataProvider rulesNumberFloatProvider
     *
     * @param array $rule
     * @param array $data
     * @param array $result
     * @param int $error
     */
    public function testFilterFloatNumber(array $rule, array $data, array $result, int $error)
    {
        $filter = new Filter($rule, $data);
        
        $this->assertEquals($error, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
        $this->assertInternalType('float', $filter->getData()['price']);
    }
    
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function rulesNumberIntProvider() : array
    {
        return [
          [['age number min 18'],['age' => '19'],['age' => 19],0],
          [['age number min 18'],['age' => '18'],['age' => 18],0],
          [['age number min 18'],['age' => '17'],['age' => 17],1],
          [['age number max 18'],['age' => '19'],['age' => 19],1],
          [['age number max 18'],['age' => '18'],['age' => 18],0],
          [['age number max 18'],['age' => '17'],['age' => 17],0]
        ];
    }
    
    /**
     * Test filter number.
     *
     * @dataProvider rulesNumberIntProvider
     *
     * @param array $rule
     * @param array $data
     * @param array $result
     * @param int $error
     */
    public function testFilterNumber(array $rule, array $data, array $result, int $error)
    {
        $filter = new Filter($rule, $data);
        
        $this->assertEquals($error, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
        $this->assertInternalType('integer', $filter->getData()['age']);
    }
}
