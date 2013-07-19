<?php

require "vendor/autoload.php";

use Rezzza\Accounting\Operation\OperationSet;
use Rezzza\Accounting\Operation\Operation;
use Rezzza\Accounting\Operation\Amount\Price;
use Rezzza\Accounting\Operation\Amount\Percentage;
use Rezzza\Accounting\Operation\Reference;
use Rezzza\Accounting\Operation\Wrapper\Factory;

$f = new Factory();

$priceWithTaxes = 283.94;
$vatRate        = 19.6;
$commissionRate = 15;

$price = new Price($priceWithTaxes, 'EUR');

$os = new OperationSet(
    array(
        'commissionExclTaxes' => new Operation($price, Operation::REMOVE, new Percentage($commissionRate)),
        'commissionVat'       => new Operation($f->compl(new Reference\Previous()), Operation::APPEND, new Percentage($vatRate)),
        'transferIncTaxes'    => new Operation($price, Operation::REMOVE, $f->val(new Reference\Previous()))
    )
);

$a = $os->compute();
echo (string) $os->getResultsSet()."\n";
exit('ici');

$price = new Price('100', 'EUR');

/*$os = new Operation(
    new Operation($price, Operation::SUB, new Percentage('10')),
    Operation::SUM,
    new Price('10', 'EUR')
);
$a = $os->compute();
print "<pre>";
var_dump($a);
print "</pre>";
exit('ici');*/

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

echo (string) $os->getResultsSet();
/*
exit('ah');
print "<pre>";
var_dump($os);
print "</pre>";
exit('ici');
 */
