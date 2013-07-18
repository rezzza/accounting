<?php

 /**
  * This file is part of the Geocoder package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Vat;

/**
 * @author Sébastien HOUZÉ <s@verylastroom.com> 
 */
class VatNumber
{
    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var array
     */
    protected $patterns = array(
        // European countries
        'AT' => '/^U[A-Z\d]{8}$/',
        'BE' => '/^0\d{9}$/',
        'BG' => '/^\d{9,10}$/',
        'CY' => '/^\d{8}[A-Z]$/',
        'CZ' => '/^\d{8,10}$/',
        'DE' => '/^\d{9}$/',
        'DK' => '/^(\d{2} ?){3}\d{2}$/',
        'EE' => '/^\d{9}$/',
        'EL' => '/^\d{9}$/',
        'ES' => '/^[A-Z]\d{7}[A-Z]|\d{8}[A-Z]|[A-Z]\d{8}$/',
        'FI' => '/^\d{8}$/',
        'FR' => '/^([A-Z]|\d){2}\d{9}$/',
        'GB' => '/^\d{9}$|^\d{12}$|^GD(8888)?[0-4]\d{2}(\d{2})?$|^HA(8888)?[5-9]\d{2}(\d{2})?$/',
        'HR' => '/^\d{11}$/',
        'HU' => '/^\d{8}$/',
        'IE' => '/^\d[0-9A-Z]\d{5}[A-Z]$/',
        'IT' => '/^\d{11}$/',
        'LT' => '/^(\d{9}|\d{12})$/',
        'LU' => '/^\d{8}$/',
        'LV' => '/^\d{11}$/',
        'MT' => '/^\d{8}$/',
        'NL' => '/^\d{9}B\d{2}$/',
        'PL' => '/^\d{10}$/',
        'PT' => '/^\d{9}$/',
        'RO' => '/^\d{2,10}$/',
        'SE' => '/^\d{12}$/',
        'SI' => '/^\d{8}$/',
        'SK' => '/^\d{10}$/',
        // Other non-EU countries
        'AL' => '/^(K|J)\d{8}[A-Z]$/',
        'AU' => '/^\d{9}$/',
        'BY' => '/^\d{9}$/',
        'CA' => '/^[A-Z]{15}$/',
        'NO' => '/^\d{9}$/',
        'PH' => '/^\d{12}$/',
        'RU' => '/^\d{10,12}$/',
        'SM' => '/^\d{5}$/',
        'RS' => '/^\d{9}$/',
        'CH' => '/^\d{6}$/',
        'TR' => '/^\d{10}$/',
        'UA' => '/^\d{12}$/',
        // Latin American countries
        'AR' => '/^\d{11}$/',
        'BO' => null,
        'BR' => '/^\d{14}$/',
        'CL' => '/^\d{8}(\d|K)?$/',
        'CO' => '/^\d{10}$/',
        'CR' => null,
        'EC' => '/^\d{13}$/',
        'SV' => null,
        'GT' => '/^\d{8}$/',
        'HN' => null,
        'MX' => '/^\d{12}$/',
        'NI' => null,
        'PA' => null,
        'PY' => null,
        'PE' => '/^\d{11}$/',
        'DO' => null,
        'UY' => null,
        'VE' => '/^[JGVE]{1}\d{9}$/',
    );

    /**
     * @param string $countryCode
     * @param string $number     
     */
    public function __construct($countryCode=null, $number=null) 
    {
        if ($countryCode !== null && $number !== null) {
            $this->setCountryCode($countryCode);
            $this->setNumber($number);
        }
    }

    /**
     * @return string
     */
    public function __toString() 
    {
        // TODO: VatNumberFormatter class to render
        return $this->getCountryCode().$this->getNumber();
    }

    /**
     * @param string $value
     */
    public function fromString($value) 
    {
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new \InvalidArgumentException('Argument must be a string or an object that can be casted into string.');
        }

        $clean = strtoupper(preg_replace('/[^A-Za-z0-9]*/', '', $value));

        $this->setCountryCode(substr($clean, 0, 2));
        $this->setNumber(substr($clean, 2));

        return $this;
    }

    /**
     * @return string $countryCode
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
    
    /**
     * @param string $countryCode 
     */
    public function setCountryCode($countryCode)
    {
        if (!is_scalar($countryCode) && !(is_object($countryCode) && method_exists($countryCode, '__toString'))) {
            throw new \InvalidArgumentException('Country code must be a string or an object that can be casted into string.');
        }

        $this->countryCode = strtoupper(preg_replace('/[^A-Za-z]*/', '', $countryCode));

        if ($this->countryCode == 'GR') {
            // Greece VAT country code is EL, weird exception
            $this->countryCode = 'EL';
        }

        if (!in_array($this->countryCode, $this->getCountryCodes())) {
            throw new InvalidCountryCodeException(sprintf('Unsupported country code (%s).', $this->countryCode));
        }

        return $this;
    }

    /**
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * @param string $number 
     */
    public function setNumber($number)
    {
        if (!is_scalar($number) && !(is_object($number) && method_exists($number, '__toString'))) {
            throw new \InvalidArgumentException('Vat number must be a string or an object that can be casted into string.');
        }

        $number = strtoupper(preg_replace('/[^A-Za-z0-9]*/', '', $number));

        if (0 === strlen($number)) {
            throw new InvalidNumberException('Invalid VAT number.');
        }

        $this->number = $number;

        return $this;
    }

    /**
     * @return array $countryCodes
     */
    public function getCountryCodes()
    {
        return array_keys($this->patterns);
    }

    /**
     * @return array $patterns
     */
    public function getPatterns() 
    {
        return $this->patterns;
    }

    /**
     * @return string $pattern
     */
    public function getPattern($countryCode)
    {
        return isset($this->patterns[$countryCode]) ? $this->patterns[$countryCode] : null;
    }
}
