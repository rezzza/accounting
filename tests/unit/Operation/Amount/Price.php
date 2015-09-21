<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\tests\unit\Operation\Amount;

use mageekguy\atoum;

use Symfony\Component\Intl\Intl;

use Rezzza\Accounting\Operation\Operation;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;
use Rezzza\Accounting\Operation\Amount\Price as TestedPrice;

/**
 * @author Sébastien HOUZÉ <s@verylastroom.com>
 */
class Price extends atoum\test
{
    public function testConstruct($value, $currencyCode, $expectedValue, $expectedCurrencyCode)
    {
        $this
            ->if($price = new TestedPrice($value, $currencyCode))
                ->float($price->getValue())
                    ->isIdenticalTo($expectedValue)
                ->string($price->getCurrency())
                    ->isIdenticalTo($expectedCurrencyCode)
            ;
    }

    public function testToString($value, $currencyCode, $expectedOutput)
    {
        if (Intl::isExtensionLoaded()) {
            \Locale::setDefault('en');
        }
        $this
            ->if($price = new TestedPrice($value, $currencyCode))
            ->string((string) $price)
            ->isIdenticalTo($expectedOutput)
            ;
    }

    public function testEquality(TestedPrice $priceA, TestedPrice $priceB, $equalityResult)
    {
        $this
            ->when(
                $result = ($priceA == $priceB)
            )
            ->then(
                $this->boolean($result)->isIdenticalTo($equalityResult)
            );
    }

    protected function testEqualityDataProvider()
    {
        return array(
            // equal
            array(new TestedPrice(1.0, 'EUR'), new TestedPrice(1.0, 'EUR'), true),
            array(new TestedPrice(15.0, 'EUR'), new TestedPrice(15.0, 'EUR'), true),
            array(new TestedPrice(1, 'USD'), new TestedPrice(1, 'USD'), true),
                // with formatter
            array(new TestedPrice(1.0, 'EUR', $this->buildFormatter('en')), new TestedPrice(1.0, 'EUR', $this->buildFormatter('fr')), true),
            // not equal
            array(new TestedPrice(1, 'USD'), new TestedPrice(1, 'EUR'), false),
            array(new TestedPrice(1, 'USD'), new TestedPrice(2, 'USD'), false),
        );
    }

    private function buildFormatter($locale)
    {
        if (!Intl::isExtensionLoaded()) {
            // This number formatter don't have exactly the same behavior
            $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        } else {
            $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        }

        return $formatter;
    }

    public function testComputeWithPrice($operation, $left, $right, $expectedResult, $expectedResultComplement)
    {
        $this
            ->if($price = new TestedPrice($left))
                ->object($result = $price->compute($operation, new TestedPrice($right)))
                    ->isInstanceOf('\Rezzza\Accounting\Operation\Amount\Result')
                ->float($result->getValue()->getValue())
                    ->isEqualTo($expectedResult)
                ->float($result->getComplement()->getValue())
                    ->isEqualTo($expectedResultComplement)
            ;
    }

    public function testComputeWithPriceExceptions($operation, $left, $right)
    {
        $this
            ->if($price = new TestedPrice($left))
                ->assert
                    ->exception(function() use($price, $right, $operation) {
                        $price->compute($operation, new TestedPrice($right));
                    })
                    ->isInstanceOf('\LogicException')
                    ->message
                        ->contains('Unsupported operator')
        ;
    }

    public function testComputeWithPercent($operation, $left, $right, $expectedResult, $expectedResultComplement)
    {
        $this
            ->if($price = new TestedPrice($left))
                ->object($result = $price->compute($operation, new \mock\Rezzza\Accounting\Operation\Amount\Percentage($right)))
                    ->isInstanceOf('\Rezzza\Accounting\Operation\Amount\Result')
                ->float($result->getValue()->getValue())
                    ->isEqualTo($expectedResult)
                ->float($result->getComplement()->getValue())
                    ->isEqualTo($expectedResultComplement)
            ;
    }

    public function testComputeWithPercentExceptions($operation, $left, $right)
    {
        $this
            ->if($price = new TestedPrice($left))
                ->assert
                    ->exception(function() use($price, $right, $operation) {
                        $price->compute($operation, new TestedPrice($right));
                    })
                    ->isInstanceOf('\LogicException')
                    ->message
                        ->contains('Unsupported operator')
        ;
    }

    protected function testConstructDataProvider()
    {
        return array(
            array(null, null, (float) 0, ''),
            array('', '', (float) 0, ''),
            array('', 'UNKNOWN', (float) 0, 'UNKNOWN'),
            array('UNKNOWN', 'UNKNOWN', (float) 0, 'UNKNOWN'),
            array(-100, 'UNKNOWN', (float)-100, 'UNKNOWN'),

            array(0, 'EUR', (float) 0, 'EUR'),
            array(100, 'EUR', (float) 100, 'EUR'),

            array(100, 'JPY', (float) 100, 'JPY'),
            array(100.50, 'JPY', (float) 100.50, 'JPY'),
            array(100.10, 'JPY', (float) 100.10, 'JPY'),
        );
    }

    protected function testToStringDataProvider()
    {
        return array(
            // FIXME: Intl component NumberFormatter don't have the same behavior than the PHP one.
            //array(null, null, '0.00'),
            //array('', '', '0.00'),
            //array('', 'UNKNOWN', '0'),
            //array('UNKNOWN', 'UNKNOWN', '0'),
            //array(-100, 'UNKNOWN', '-100'),

            array(0, 'EUR', '€0.00'),
            array(100, 'EUR', '€100.00'),

            array(100, 'JPY', '¥100'),
            array(100.50, 'JPY', '¥100'),
            array(100.10, 'JPY', '¥100'),
        );
    }

    protected function testComputeWithPriceExceptionsDataProvider()
    {
        return array(
            array(Operation::EXONERATE, 100, 10),
        );
    }

    protected function testComputeWithPriceDataProvider()
    {
        return array(
            // Majorations
            array(Operation::MAJORATE, '', 10, (float)10, (float)-10),
            array(Operation::MAJORATE, null, 10, (float)10, (float)-10),
            array(Operation::MAJORATE, 0, 10, (float)10, (float)-10),
            array(Operation::MAJORATE, 100, 10, (float)110, (float)-10),
            array(Operation::MAJORATE, 100.54, 10, (float)110.54, (float)-10),
            array(Operation::MAJORATE, 100.55, 10, (float)110.55, (float)-10),
            array(Operation::MAJORATE, 100.56, 10, (float)110.56, (float)-10),
            array(Operation::MAJORATE, 100, 10.54, (float)110.54, (float)-10.54),
            array(Operation::MAJORATE, 100, 10.55, (float)110.55, (float)-10.55),
            array(Operation::MAJORATE, 100, 10.56, (float)110.56, (float)-10.56),
            // Minorations
            array(Operation::MINORATE, '', 10, (float)-10, (float)10),
            array(Operation::MINORATE, null, 10, (float)-10, (float)10),
            array(Operation::MINORATE, 0, 10, (float)-10, (float)10),
            array(Operation::MINORATE, 100, 10, (float)90, (float)10),
            array(Operation::MINORATE, 100.54, 10, (float)90.54, (float)10),
            array(Operation::MINORATE, 100.55, 10, (float)90.55, (float)10),
            array(Operation::MINORATE, 100.56, 10, (float)90.56, (float)10),
            array(Operation::MINORATE, 100, 10.54, (float)89.46, (float)10.54),
            array(Operation::MINORATE, 100, 10.55, (float)89.45, (float)10.55),
            array(Operation::MINORATE, 100, 10.56, (float)89.44, (float)10.56),
        );
    }

    protected function testComputeWithPercentDataProvider()
    {
        return array(
            array(Operation::MAJORATE, 0, 10, (float)0, (float)0),
            array(Operation::MINORATE, 0, 10, (float)0, (float)0),
            array(Operation::EXONERATE, 0, 10, (float)0, (float)0),

            array(Operation::MAJORATE, 100, 10, (float)110, (float)10),
            array(Operation::MAJORATE, 100.54, 10, (float)110.59, (float)10.05),
            array(Operation::MINORATE, 100, 10, (float)90, (float)10),
            array(Operation::EXONERATE, 100, 10, (float)90.91, (float)9.09),

            array(Operation::MAJORATE, -100, 10, (float)-110, (float)10),
            array(Operation::MAJORATE, -100.54, 10, (float)-110.59, (float)10.05),
            array(Operation::MINORATE, -100, 10, (float)-90, (float)10),
            array(Operation::EXONERATE, -100, 10, (float)-90.91, (float)9.09),
        );
    }

    protected function testComputeWithPercentExceptionsDataProvider()
    {
        return array(
            array('foo', 100, 10),
        );
    }
}
