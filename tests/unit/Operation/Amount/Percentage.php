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
use Rezzza\Accounting\Operation\Amount\Price;
use Rezzza\Accounting\Operation\Amount\Percentage as TestedPercentage;

/**
 * @author Sébastien HOUZÉ <s@verylastroom.com>
 */
class Percentage extends atoum\test
{
    public function testConstruct($value, $expectedValue)
    {
        $this
            ->if($percentage = new TestedPercentage($value))
                ->float($percentage->getValue())
                    ->isIdenticalTo($expectedValue)
            ->if($level = $percentage->getLevel())
                ->integer($level)
                    ->isIdenticalTo(0)
            ;
    }

    public function testToString($value, $expectedValue)
    {
        $this
            ->if($percentage = new TestedPercentage($value))
            ->string((string) $percentage)
            ->isIdenticalTo($expectedValue)
            ;
    }

    public function testComputeWithPercentage($operation, $left, $right, $expectedResult, $expectedResultComplement)
    {
        $this
            ->if($percentage = new TestedPercentage($left))
                ->object($result = $percentage->compute($operation, new TestedPercentage($right)))
                    ->isInstanceOf('\Rezzza\Accounting\Operation\Amount\Result')
                ->float($result->getValue()->getValue())
                    ->isEqualTo($expectedResult)
                ->float($result->getComplement()->getValue())
                    ->isEqualTo($expectedResultComplement)
            ;
    }

    public function testComputeWithPriceException($operation, $left, $right)
    {
        $this
            ->if($percentage = new TestedPercentage($left))
                ->assert
                    ->exception(function() use($percentage, $right, $operation) {
                        $percentage->compute($operation, new Price($right));
                    })
                    ->isInstanceOf('\LogicException')
                    ->message
                    ->contains('Unsupported operand')
        ;
    }

    public function testComputeWithBadOperationException($operation)
    {
        $this
            ->if($percentage = new TestedPercentage(10))
                ->assert
                    ->exception(function() use($percentage, $operation) {
                        $percentage->compute($operation, new TestedPercentage(10));
                    })
                    ->isInstanceOf('\LogicException')
                    ->message
                    ->contains('Unsupported operation')
        ;
    }

    protected function testComputeWithBadOperationExceptionDataProvider()
    {
        return array(
            Operation::EXONERATE,
        );
    }

    protected function testComputeWithPriceExceptionDataProvider()
    {
        return array(
            array(Operation::MAJORATE, 100, 10),
            array(Operation::MINORATE, 100, 10),
        );
    }

    protected function testComputeWithPercentageDataProvider()
    {
        return array(
            // Majorations
            array(Operation::MAJORATE, null, 10, (float)-10, (float)110),
            array(Operation::MAJORATE, '', 10, (float)-10, (float)110),
            array(Operation::MAJORATE, 0, 10, (float)-10, (float)110),
            array(Operation::MAJORATE, 10, 10, (float)0, (float)100),
            array(Operation::MAJORATE, 10.5, 10, (float)0.5, (float)99.5),
            // Minorations
            array(Operation::MINORATE, null, 10, (float)10, (float)90),
            array(Operation::MINORATE, '', 10, (float)10, (float)90),
            array(Operation::MINORATE, 0, 10, (float)10, (float)90),
            array(Operation::MINORATE, 10, 10, (float)20, (float)80),
            array(Operation::MINORATE, 10.5, 10, (float)20.5, (float)79.5),
        );
    }

    protected function testConstructDataProvider()
    {
        return array(
            array(null, (float) 0),
            array(0.0, (float) 0),
            array(0.5, (float) 0.5),
            array((float) 0, (float) 0),
            array('', (float) 0),
            array(-100, (float)-100),
        );
    }

    protected function testToStringDataProvider()
    {
        return array(
            array(null, '0%'),
            array('', '0%'),
            array('0', '0%'),
            array('100', '100%'),
        );
    }
}
