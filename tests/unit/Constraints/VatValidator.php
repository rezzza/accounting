<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\tests\unit\Constraints;

use mageekguy\atoum;

use Symfony\Component\Validator\Validation;
use Rezzza\Accounting\Constraints\Vat;

/**
 * @author Sébastien HOUZÉ <s@verylastroom.com>
 */
class VatValidator extends atoum\test
{
    public function testValidNumber($number)
    {
        $this
            ->if($validator = Validation::createValidator())
            ->object(($validator->validateValue($number, new Vat)))
            ->isInstanceOf('Symfony\Component\Validator\ConstraintViolationList')
            ->hasSize(0)
            ;
    }

    public function testInvalidNumber($number)
    {
        $this
            ->if($validator = Validation::createValidator())
            ->object(($validator->validateValue($number, new Vat)))
            ->isInstanceOf('Symfony\Component\Validator\ConstraintViolationList')
            ->hasSize(1)
            ;
    }

    protected function testValidNumberDataProvider()
    {
        return array(
            'ATU12345675',
            'ATU12345620',
            'ALK99999999L',
            'ALK41424801U',
            'ALK11715005L',
            'AR00000000000',
            'BE0123456749',
            'BE0897290877',
            'BG1234567892',
            'BG175074752',
            'BG131202360',
            'BG040683212',
            'BG1001000000',
            'CL334441113',
            'CL334441180',
            'CL33444113K',
            'CO9001279338',
            'CO9001279320',
            'CY12345678F',
            'CZ12345679',
            'CZ10001000',
            'CZ10000101',
            'CZ612345670',
            'CZ991231123',
            'CZ6306150004',
            'CZ6306150004',
            'DE123456788',
            'DE123456770',
            'DK12345674',
            'EE123456780',
            'ESA12345674',
            'ESP1234567D',
            'ESK1234567L',
            'ESR9600075G',
            'ESW4003922D',
            'ESV99218067',
            'ESU99216632',
            'ESJ99216582',
            'ESU99216426',
            'ES12345678Z',
            'ESX5277343Q',
            'ESY5277343F',
            'ESZ5277343K',
            'ESA12345690',
            'FI12345671',
            'FR2H123456789',
            'FR83404833048',
            'FR88534322359',
            'FR89534322359',
            'FR23123456789',
            'GBGD123',
            'GBGD888812326',
            'GBHA567',
            'GBHA888856782',
            'GB123456782',
            'GB102675046',
            'GB100190874',
            'GB003232345',
            'GB001123456782',
            'GB242338087388',
            'GR123456783',
            'HR12345678903',
            'HR24595836665',
            'HR23448731483',
            'HU12345676',
            'IE7A12345J',
            'IE1234567T',
            'IT12345670017',
            'IT00118439991',
            'LT123456715',
            'LT123456789011',
            'LU12345613',
            'LV41234567891',
            'LV15066312345',
            'MT12345634',
            'NL123456782B90',
            'PL1234567883',
            'PT123456789',
            'RO24736200',
            'RO1234567897',
            'RU5505035011',
            'RU550501929014',
            'SE123456789701',
            'SE556728341001',
            'SI12345679',
            'SK0012345675',
            'SK0012345678',
            'SK6306151234',
            'SK2021853504',
            'SM12345',
            'UA123456789012',
        );
    }

    protected function testInValidNumberDataProvider()
    {
        return array(
            'ATU123456789',
            'ATA12345675',
            //'ATUA2345675',
            //'ATU12345678',
            'ALK999999999L',
            'ALAA9999999L',
            'ALKA9999999L',
            'ALK999999991',
            //'AR00000000001',
            'BE01234567490',
            'BE9123456749',
            'BE0A23456749',
            //'BE0123456700',
            //'BG12345678921',
            //'BGA234567892',
            //'BG2234567892',
            //'BG0000003000',
            //'BG1234567890',
            'CLA34441113',
            'CO900127933',
            'COA001279338',
            'CY2345678F',
            'CYA2345678F',
            //'CY12345678A',
            'CZ1234567',
            'CZA2345679',
            //'CZ92345679',
            //'CZ12345670',
            //'CZ612345679',
            //'CZ541231123',
            //'CZ791231123',
            //'CZ990031123',
            //'CZ991331123',
            //'CZ995031123',
            //'CZ996331123',
            //'CZ990200123',
            //'CZ995229123',
            //'CZ965200123',
            //'CZ960230123',
            //'CZ990400123',
            //'CZ990431123',
            //'CZ990100123',
            //'CZ990132123',
            //'CZ5306150004',
            //'CZ6300150004',
            //'CZ6313150004',
            //'CZ6350150004',
            //'CZ6363150004',
            //'CZ6302000004',
            //'CZ6302290004',
            //'CZ6402000004',
            //'CZ6402310004',
            //'CZ6304000004',
            //'CZ6304310004',
            //'CZ6301000004',
            //'CZ6301320004',
            //'CZ6306150000',
            'DE12345678',
            'DEA23456788',
            //'DE000000088',
            //'DE123456789',
            'DK1234564',
            'DKA2345674',
            //'DK02345674',
            //'DK12345679',
            'EE1234567890',
            'EEA23456780',
            //'EE123456789',
            'ES1234567890',
            'ESAB3456789',
            //'ESA12345679',
            'ESWA003922D',
            //'ESW4003922A',
            'ESZA277343K',
            //'ESZ5277343A',
            'ES1A345678Z',
            //'ES12345678A',
            //'RO-7793957',
            //'12456789', // woot
        );
    }
}
