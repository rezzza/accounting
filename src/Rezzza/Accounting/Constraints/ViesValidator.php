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
class ViesValidator extends ConstraintValidator
{
    /**
     * @var string
     */
    protected $wsdl = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * @var \SoapClient
     */
    protected $soapClient;

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
        }

        try {
            $result = $this->getSoapClient()->checkVat(
                array(
                    'countryCode' => $vatNumber->getCountryCode(),
                    'vatNumber'   => $vatNumber->getNumber()
                )
            );

            if ($result->valid != 1) {
                $this->context->addViolation($constraint->message, array('{{ countryCode }}' => $vatNumber->getCountryCode()));
            }
        } catch (\SoapException $e) {
            $this->context->addViolation($constraint->remoteError);

            throw $e;
        }
    }

    /**
     * @return \SoapClient $soapClient
     */
    protected function getSoapClient()
    {
        if (null === $this->soapClient) {
            if (!class_exists('\SoapClient')) {
                throw new \RuntimeException('You have to install php soap extension to use validator "'.__CLASS__.'"');
            }

            $this->soapClient = new \SoapClient($this->wsdl, array(
                'user_agent' => 'VatConstraintAgent'
            ));
        }

        return $this->soapClient;
    }
}
