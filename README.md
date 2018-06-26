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
| Name           | Description                                      | Rule Arguments | Operators                         | Example Data from `$_POST`   | Example Rule                             |
|----------------|--------------------------------------------------|----------------|-----------------------------------|------------------------------|------------------------------------------|
| Date           | Check for a valid date                           | 1              | none                              | `['born'] = '1980-06-01'`    | `'born: date Y-m-d'`                     |
| DateCompare    | Compare one date with another                    | 3              | >, <, >=, <=, =                   | `['born'] = '1980-06-01'`    | `'born: datecompare < Y-m-d 1990-01-01'` |
| Email          | Check for a valid email                          | 0              | none                              | `['email'] = 'foo@mail.com'` | `'email: email'`                         |
| Escape         | Convert special chars in html entities           | 0              | none                              | `['name'] = 'foo<script>'`   | `'name: escape'`                         |
| Number         | Check for a valid number                         | 0              | none                              | `['age'] = 25`               | `'age: number'`                          |
| NumberCompare  | Compare one number with another                  | 2              | >, <, >=, <=, =                   | `['age'] = 25`               | `'age: numbercompare > 18'`              |
| NumberInterval | Check if a number is included or not on interval | 3              | <>, ><, <=>, >=<                  | `['age'] = 25`               | `'age: numberinterval >< 18 80'`         |
| Required       | Check for null values                            | 0              | none                              | `['name'] = 'foo'`           | `'name: required'`                       |
| StringCompare  | Compare one string with another                  | 2              | len>, len<, len>=, len<=, len=, = | `['name'] = 'foo'`           | `'name: stringcompare len> 2'`           |

### Operators
| Filter         | Operator | Description                   | Notes                             |
|----------------|----------|-------------------------------|-----------------------------------|
| DateCompare    | <        | less than                     |                                   |
| DateCompare    | >        | greater than                  |                                   |
| DateCompare    | <=       | less than or equal            |                                   |
| DateCompare    | >=       | greater than or equal         |                                   |
| DateCompare    | =        | equal                         | PHP === equal                     |
| NumberCompare  | <        | less than                     |                                   |
| NumberCompare  | >        | greater than                  |                                   |
| NumberCompare  | <=       | less than or equal            |                                   |
| NumberCompare  | >=       | greater than or equal         |                                   |
| NumberInterval | <>       | out interval, exclusive       | 8-10: 7, 11 true - 8, 9, 10 false |
| NumberInterval | ><       | in interval, exclusive        | 8-10: 9 true - 7, 8, 10, 11 false |
| NumberInterval | <=>      | out interval, inclusive       | 8-10: 7, 8, 10, 11 true - 9 false |
| NumberInterval | >=<      | in interval, inclusive        | 8-10: 8, 9, 10 true - 7, 11 false |
| StringCompare  | len<     | length less than              | PHP strlen(string) < number       |
| StringCompare  | len>     | length greater than           | PHP strlen(string) > number       |
| StringCompare  | len<=    | length less than or equal     | PHP strlen(string) <= number      |
| StringCompare  | len>=    | length greather than or equal | PHP strlen(string) >= number      |
| StringCompare  | len=     | length equal                  | PHP strlen(string) === number     |
| StringCompare  | =        | equal                         | PHP === equal                     |

## Usage
Filters can be used in two different ways.

### Filter One
Apply one or more rules to one value:

```php
use Linna\Filter\Filter;

$filter = new Filter();
$filter->filterOne(20, 'number between 15 25');

//int 0
var_dump($filter->getErrors());

//array empty
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

//setting rules
$rules = [
    'email: required, email',
    'password: required, stringcompare len>= 12',
    'age: number, numbercompare < 30',
    'born: date Y-m-d, datecompare <= Y-m-d 1990-12-31',
];

//create instance
$filter = new Filter();
$filter->filterMulti($_POST, $rules);

//int 0
var_dump($filter->getErrors());

//array empty
var_dump($filter->getMessages());

//filtered data
//array (size=4)
//  'email' => string 'pippo@gmail.com' (length=15)
//  'password' => string 'p4ssw0rd200!' (length=12)
//  'age' => int 25
//  'born' => string '1980-06-01' (length=10)
var_dump($filter->getData());
```