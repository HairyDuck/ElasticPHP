

What is miniES
====================

A simpler alternative to AWS SDK miniES is a PHP interface to AWS API.
It is easy to learn, use and modify, unlike other large and complex libraries.

 * Small and fast
 * Doesn't require PEAR or COMPOSER
 * Completely stand-alone (doesn't require AWS SDK)

How to use
----------

### Amazon ES

```php
<?php
require('miniES.php');
 $ES = new ES();
    $ES->setDomain('MYDOMAIN');
    $ES->setIndex('MYINDEX');


    $search = array (
      'sort' => 
      array (
        0 => '_score',
        1 => 
        array (
          'ranking' => 'desc',
        ),
      ),
      'query' => 
      array (
        'bool' => 
        array (
          'must' => 
          array (
            'match' => 
            array (
              'ss' => 'SOMETHING',
            ),
          ),
          'filter' => 
          array (
            'terms' => 
            array (
              'categoryIDs' => 
              array (
                0 => 3159,
              ),
            ),
          ),
        ),
      ),
    );


    $Response = $ES->search($search);


print_r($Response);

```

Requirements
------------

 * PHP >= 5.2.0
 * extensions: cURL, json

Developer Documentation
-----------------------

### Amazon ES

* [Operations in Amazon ES](https://docs.aws.amazon.com/elasticsearch-service/latest/developerguide/what-is-amazon-elasticsearch-service.html)

License
-------

The MIT License

Copyright (c) 2018 Luke Addington

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
