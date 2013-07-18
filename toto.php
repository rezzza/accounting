<?php

require "vendor/autoload.php";

use Rezzza\Accounting\Operation\OperationSet;
use Rezzza\Accounting\Operation\Operation;
use Rezzza\Accounting\Operation\Amount\Price;
use Rezzza\Accounting\Operation\Amount\Percentage;
use Rezzza\Accounting\Operation\Reference;
use Rezzza\Accounting\Operation\Wrapper\Factory;

$f = new Factory();

$price = new Price('100', 'EUR');

$os = new OperationSet(
    array(
        new Operation($price, Operation::SUB, new Percentage('10')), // 90.9090
        new OperationSet(array(
            new Operation($f->val(new Reference\Previous()), Operation::SUB, new Price('15', 'EUR')), // 75.9090909
            new Operation($f->val(new Reference\Reference(1)), Operation::SUM, new Price('10', 'EUR')), // 85.9090909
        ))
    )
);
$a = $os->compute();
print "<pre>";
var_dump($os->getResultsSet());
print "</pre>";
/*
exit('ah');
print "<pre>";
var_dump($os);
print "</pre>";
exit('ici');
 */
