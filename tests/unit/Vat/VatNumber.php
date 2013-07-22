<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\tests\unit\Vat;

use mageekguy\atoum;
use Rezzza\Accounting\Vat\VatNumber as TestedVatNumber;

/**
 * @author Sébastien HOUZÉ <s@verylastroom.com>
 */
class VatNumber extends atoum\test
{
    public function testConstruct($countryCode, $number, $expectedOutput)
    {
        $this
            ->if($vatNumber = new TestedVatNumber($countryCode, $number))
                ->string((string) $vatNumber)
                    ->isIdenticalTo($expectedOutput)
            ;
    }

    public function testFromString($input, $expectedOutput)
    {
        $this
            ->if($vatNumber = new TestedVatNumber)
                ->string((string) $vatNumber->fromString($input))
                    ->isIdenticalTo($expectedOutput)
            ;
    }

    public function testValidCountryCode($countryCode, $expectedCountryCode)
    {
        $this
            ->if($vatNumber = new TestedVatNumber)
                ->and($vatNumber->setCountryCode($countryCode))
                ->string($vatNumber->getCountryCode())
                    ->isIdenticalTo($expectedCountryCode)
        ;
    }

    public function testInvalidTypeCountryCode($countryCode)
    {
        $this
            ->if($vatNumber = new TestedVatNumber)
                ->assert
                    ->exception(function() use ($vatNumber, $countryCode) {
                        $vatNumber->setCountryCode($countryCode);
                    })
                    ->isInstanceOf('\InvalidArgumentException')
                    ->hasMessage('Country code must be a string or an object that can be casted into string.');
            ;
    }

    public function testInvalidCountryCode($countryCode)
    {
        $this
            ->if($vatNumber = new TestedVatNumber)
                ->assert
                    ->exception(function() use ($vatNumber, $countryCode) {
                        $vatNumber->setCountryCode($countryCode);
                    })
                    ->isInstanceOf('\Rezzza\Accounting\Vat\InvalidCountryCodeException')
                    ->message
                        ->contains('Unsupported country code ');
            ;
    }

    public function testValidNumber($number, $expectedNumber)
    {
        $this
            ->if($vatNumber = new TestedVatNumber)
                ->and($vatNumber->setNumber($number))
                ->string($vatNumber->getNumber())
                    ->isIdenticalTo($expectedNumber)
        ;
    }

    public function testInvalidTypeNumber($number)
    {
        $this
            ->if($vatNumber = new TestedVatNumber)
                ->assert
                    ->exception(function() use ($vatNumber, $number) {
                        $vatNumber->setNumber($number);
                    })
                    ->isInstanceOf('\InvalidArgumentException')
                    ->hasMessage('Vat number must be a string or an object that can be casted into string.');
            ;
    }

    public function testInvalidNumber($number)
    {
        $this
            ->if($vatNumber = new TestedVatNumber)
                ->assert
                    ->exception(function() use ($vatNumber, $number) {
                        $vatNumber->setNumber($number);
                    })
                    ->isInstanceOf('\Rezzza\Accounting\Vat\InvalidNumberException')
                    ->hasMessage('Invalid VAT number.');
            ;
    }

    protected function testConstructDataProvider() {
        return array(
            array('FR', ' 89 534322359', 'FR89534322359'),
            array(' FR', '-89-5343 22359 ', 'FR89534322359'),
            array('BE', '0477 295 923', 'BE0477295923'),
            array('RO', '12', 'RO12'),
            array('ro', '12', 'RO12'),
        );
    }

    protected function testFromStringDataProvider()
    {
        return array(
            array('FR 89 534322359', 'FR89534322359'),
            array(' FR-89-5343 22359 ', 'FR89534322359'),
            array('BE 0477 295 923', 'BE0477295923'),
            array('RO 12', 'RO12'),
            array('ro 12', 'RO12'),
            array('´Éro 12', 'RO12'),
        );
    }

    protected function testValidNumberDataProvider()
    {
        return array(
            array('GD123', 'GD123'),
            array('Gd123', 'GD123'),
            array('GD 123', 'GD123'),
            array(' GD123', 'GD123'),
            array('GD123 ', 'GD123'),
            array('/GD123', 'GD123'),
            array('GD/123', 'GD123'),
            array('GD123/', 'GD123'),
            array('.GD123', 'GD123'),
            array('GD.123', 'GD123'),
            array('GD123.', 'GD123'),
            array('-GD123', 'GD123'),
            array('GD-123', 'GD123'),
            array('GD123-', 'GD123'),
            array('_GD123', 'GD123'),
            array('GD_123', 'GD123'),
            array('GD123_', 'GD123'),
            array('GD§123', 'GD123'),
            array('§GD123', 'GD123'),
            array('GD123§', 'GD123'),
            array('ÉGD123', 'GD123'),
            array('ÉGD123', 'GD123'),
            array('öG¨d123', 'GD123'),
        );
    }

    protected function testInvalidTypeCountryCodeDataProvider()
    {
        return array(
            array(null),
            array(new \StdClass),
            array(array()),
        );
    }

    protected function testInvalidCountryCodeDataProvider()
    {
        return array(
            array(''),
            array('CN'),
            array('TK'),
        );
    }

    protected function testValidCountryCodeDataProvider()
    {
        $countryCodes = array();

        $vatNumber = new TestedVatNumber;
        foreach ($vatNumber->getCountryCodes() as $countryCode) {
            $countryCodes[] = array($countryCode, $countryCode);
            $countryCodes[] = array((string) new TestedVatNumber($countryCode, '1'), $countryCode);

            if ('EL' === $countryCode) {
                $countryCodes[] = array('GR', 'EL');
                $countryCodes[] = array((string) new TestedVatNumber('GR', '1'), 'EL');
            }
        }

        return $countryCodes;
    }

    protected function testInvalidTypeNumberDataProvider()
    {
        return array(
            array(null),
            array(new \StdClass),
            array(array()),
        );
    }

    protected function testInvalidNumberDataProvider()
    {
        return array(
            array(''),
            array(' '),
            array("\t"),
            array('    -  '),
            array('/'),
            array('-'),
            array('$'),
            array('€'),
            array('é'),
            array('à'),
        );
    }
}
