![Linna Filter](logo-filter.png)
<br/>
<br/>
<br/>
[![Build Status](https://travis-ci.org/linna/filter.svg?branch=master)](https://travis-ci.org/linna/filter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linna/filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/linna/filter/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/linna/filter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/linna/filter/?branch=master)
[![StyleCI](https://styleci.io/repos/111321128/shield?branch=master&style=flat)](https://styleci.io/repos/111321128)


This package provide filters for user input data.

## Requirements
This package require php 7.1

## Installation
With composer:
```
composer require linna/filter
```

## Available Filters

### Filters
| Rule Name        | Aliases        | Description                                      | Rule Arguments | Operators           | Example Data from `$_POST`   | Example Rule                             |
|------------------|----------------|--------------------------------------------------|----------------|---------------------|------------------------------|------------------------------------------|
| date             | dat, d         | Check for a valid date                           | 1              | none                | `['born'] = '1980-06-01'`    | `'born: date Y-m-d'`                     |
| datecompare      | datcmp, dc     | Compare one date with another                    | 3              | >, <, >=, <=, =     | `['born'] = '1980-06-01'`    | `'born: datecompare < Y-m-d 1990-01-01'` |
| email            | mail, e@       | Check for a valid email                          | 0              | none                | `['email'] = 'foo@mail.com'` | `'email: email'`                         |
| escape           | escp, es       | Convert special chars in html entities           | 0              | none                | `['name'] = 'foo<script>'`   | `'name: escape'`                         |
| number           | num, n         | Check for a valid number and cast to number      | 0              | none                | `['age'] = 25`               | `'age: number'`                          |
| numbercompare    | numcmp, nc     | Compare one number with another                  | 2              | >, <, >=, <=, =     | `['age'] = 25`               | `'age: numbercompare > 18'`              |
| numberinterval   | numint, ni     | Check if a number is included or not on interval | 3              | <>, ><, <=>, >=<    | `['age'] = 25`               | `'age: numberinterval >< 18 80'`         |
| required         | req, rq        | Check for null values                            | 0              | none                | `['name'] = 'foo'`           | `'name: required'`                       |
| str              | string, s      | Cast to string                                   | 0              | none                | `['name'] = 'foo'`           | `'name: str'`                            |
| stringlencompare | strlencmp, slc | Check the length of a string                     | 2              | >, <, >=, <=, =, != | `['name'] = 'foo'`           | `'name: stringlencompare > 2'`           |

A rule could be called with the name or with the alias. An alias help to write rules more quickly.

```php
//using rule name
$rule = 'age: number, numbercompare < 30';

//using alias
$rule = 'age: n, nc < 30';
```

### Operators
| Filter           | Operator | Description                   | Notes                             |
|------------------|----------|-------------------------------|-----------------------------------|
| DateCompare      | <        | less than                     |                                   |
|                  | >        | greater than                  |                                   |
|                  | <=       | less than or equal            |                                   |
|                  | >=       | greater than or equal         |                                   |
|                  | =        | equal                         | PHP === equal                     |
| NumberCompare    | <        | less than                     |                                   |
|                  | >        | greater than                  |                                   |
|                  | <=       | less than or equal            |                                   |
|                  | >=       | greater than or equal         |                                   |
| NumberInterval   | <>       | out interval, exclusive       | 8-10: 7, 11 true - 8, 9, 10 false |
|                  | ><       | in interval, exclusive        | 8-10: 9 true - 7, 8, 10, 11 false |
|                  | <=>      | out interval, inclusive       | 8-10: 7, 8, 10, 11 true - 9 false |
|                  | >=<      | in interval, inclusive        | 8-10: 8, 9, 10 true - 7, 11 false |
| StringLenCompare | <        | length less than              | PHP strlen(string) < number       |
|                  | >        | length greater than           | PHP strlen(string) > number       |
|                  | <=       | length less than or equal     | PHP strlen(string) <= number      |
|                  | >=       | length greather than or equal | PHP strlen(string) >= number      |
|                  | =        | length equal                  | PHP strlen(string) === number     |
|                  | !=       | length not equal              | PHP strlen(string) !== number     |

## Usage
Filters can be used in two different ways.

### Filter One
Apply one or more rules to one value:

```php
use Linna\Filter\Filter;

$filter = new Filter();
$filter->filterOne(20, 'number numberinterval >< 15 25');

//int 0
var_dump($filter->getErrors());

//messages
//array (size=1)
//  'data' => 
//    array (size=0)
//      empty
var_dump($filter->getMessages());

//filtered data
//array (size=1)
//  'data' => int 20
var_dump($filter->getData());
```

### Filter Multi
Apply one or more rules to many values:

```php
use Linna\Filter\Filter;

//simulate data from user form
$_POST = [
    'email' => 'user@email.com',
    'password' => 'p4ssw0rd200!',
    'age' => '25',
    'born' => '1980-06-01',
];

//create instance
$filter = new Filter();
$filter->filterMulti($_POST, [
    'email: required, email',
    'password: required, stringlencompare >= 12',
    'age: number, numbercompare < 30',
    'born: date Y-m-d, datecompare <= Y-m-d 1990-12-31',
]);

//int 0
var_dump($filter->getErrors());

//messages
//array (size=4)
//  'email' => 
//    array (size=0)
//      empty
//  'password' => 
//    array (size=0)
//      empty
//  'age' => 
//    array (size=0)
//      empty
//  'born' => 
//    array (size=0)
//      empty
var_dump($filter->getMessages());

//filtered data
//array (size=4)
//  'email' => string 'pippo@gmail.com' (length=15)
//  'password' => string 'p4ssw0rd200!' (length=12)
//  'age' => int 25
//  'born' => string '1980-06-01' (length=10)
var_dump($filter->getData());
```

## Retriving results
There are two ways for get results from filter.

Using methods from `Filter` instance.
```php
use Linna\Filter\Filter;

$filter = new Filter();
$filter->filterOne(20, 'number numberinterval >< 15 25');

$errors = $filter->getErrors();
$messages = $filter->getMessages();
$data = $filter->getData();
```

Using result object:
```php
use Linna\Filter\Filter;

$filter = new Filter();
$result = $filter->filterOne(20, 'number numberinterval >< 15 25');

//or with a single expression
$result = (new Filter())->filterOne(20, 'number numberinterval >< 15 25');

$errors = $result->errors();
$messages = $result->messages();
$data = $result->data();
```

## Rule syntax
Parser can accept rules formatted in varius way.  

First word must be the name of the input, same present as index in input array.
```php
//simulate data from user form
$_POST = [
    'email' => 'pippo@gmail.com',
    'password' => 'p4ssw0rd200!',
    'age' => '25',
    'born' => '1980-06-01',
];

$rules = [
    'email required email',
    'password required stringlencompare >= 12',
    'age number numberinterval >=< 20 30',
    'born date Y-m-d datecompare <= Y-m-d 1990-12-31',
];
```

Can be used for separate the words and params of rules this chars: `:` `;` `,`

Input name separator `:`
```php
$rules = [
    'email: required email',
    'password: required stringlencompare >= 12',
    'age: number numberinterval >=< 20 30',
    'born: date Y-m-d datecompare <= Y-m-d 1990-12-31',
];
```

Input name separator `:` 
Rules separator `,`
```php
$rules = [
    'email: required, email',
    'password: required, stringlencompare >= 12',
    'age: number, numberinterval >=< 20 30',
    'born: date Y-m-d, datecompare <= Y-m-d 1990-12-31',
];
```

Input name separator `:`
Rules separator `;`
Rule arguments separator `,`
```php
$rules = [
    'email: required; email',
    'password: required; stringlencompare >=, 12',
    'age: number; numberinterval >=<, 20, 30',
    'born: date Y-m-d; datecompare <=, Y-m-d, 1990-12-31',
];
```

Must be used for params that contain spaces one of this chars: `"` `'`
```php
$rules = [
    'email: required email',
    'password: required stringlencompare >= 12',
    'age: number numberinterval >=< 20 30',
    'born: date "Y m d" datecompare <= "Y m d" "1990 12 31"',
];

$rules = [
    "email: required email",
    "password: required stringlencompare >= 12",
    "age: number numberinterval >=< 20 30",
    "born: date \"Y m d\" datecompare <= \"Y m d\" \"1990 12 31\"",
];

$rules = [
    "email: required email",
    "password: required stringlencompare >= 12",
    "age: number numberinterval >=< 20 30",
    "born: date 'Y m d' datecompare <= 'Y m d' '1990 12 31'",
];

$rules = [
    'email: required email',
    'password: required stringlencompare >= 12',
    'age: number numberinterval >=< 20 30',
    'born: date \'Y m d\' datecompare <= \'Y m d\' \'1990 12 31\'',
];
```