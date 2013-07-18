<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @author Sébastien HOUZÉ <s@verylastroom.com>
 */
class Vat extends Constraint
{
    public $incorrectCountryCodeMessage = 'The country code part of the VAT number ({{ countryCode }}) is incorrect.';
    public $invalidMessage              = 'Invalid VAT number.';
}
