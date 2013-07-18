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
class Vies extends Constraint
{
    public $message     = 'Invalid European VAT number for country {{ countryCode }}.';
    public $remoteError = 'The VIES online service used to check european VAT numbers seems to have some issues, please retry later.';
}
