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
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use Rezzza\Accounting\Vat\VatNumber;

/**
 * @author Sébastien HOUZÉ <s@verylastroom.com> 
 */
class VatValidator extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint) 
    {
        if (null === $value || '' === $value) {
            return;
        }

        $vatNumber = new VatNumber;

        try {
            $value = (string) $vatNumber->fromString($value);
        } catch (\InvalidArgumentException $e) {
            throw new UnexpectedTypeException($value, 'string');
        } catch (Vat\InvalidCountryCodeException $e) {
            $this->context->addViolation($constraint->incorrectCountryCodeMessage, array('{{ countryCode }}' => $vatNumber->getCountryCode()));
        } catch (Vat\InvalidNumberException $e) {
            $this->context->addViolation($constraint->invalidMessage);
        }

        $pattern = $vatNumber->getPattern($vatNumber->getCountryCode());

        if (null !== $pattern && !preg_match($pattern, $vatNumber->getNumber())) {
            $this->context->addViolation($constraint->invalidMessage);
        }

        //$validateForCountryCB = 'validateForCountry'.$vatNumber->getCountryCode();
        //if (method_exists($this, $validateForCountryCB)) {
            //if (!$this->$validateForCountryCB($vatNumber)) {
                ////
                //$this->context->addViolation();
            //}
        //}
    }

    protected function validateForCountryFR($vatNumber)
    {
        $system = 'old';
        $number = substr($vatNumber, 2, 11);
        $siren  = (int) substr($vatNumber, 4);

        if (false && $system === 'old') {
            $check = (int) ((12 + 3 * ($siren % 97)) % 97);

            return ($check === (int) substr($vatNumber, 2, 2));
        } else {
            $check1 = (int) $number[0];
            $check2 = (int) $number[1];

            if ($check1 < 10) {
                $checkSum = ($check1 * 24 + $check2 - 10);
            } else {
                $checkSum = ($check1 * 34 + $check2 - 100);
            }

            $modX = $checkSum % 11;
            $checkSum = ($checkSum / 11) + 1;

            $modY = ($number + $checkSum) % 11;

            return ($modY === $modX);
        }
    }

    private function multAdd($i, $j) 
    {
        $mult = $i * $j;
        $res = 0;

        $len = strlen($mult);
        for ($k = 0; $k < $len; $k++) {
            $res += $mult[$k];
        }

        return $res;
    }
}
