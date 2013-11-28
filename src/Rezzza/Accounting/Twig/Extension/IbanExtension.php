<?php

namespace Rezzza\Accounting\Twig\Extension;

/**
 * IbanExtension
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class IbanExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'iban' => new \Twig_Filter_Method($this, 'formatIban', array('is_safe' => array('html'))),
        );
    }

    /**
     * @param string $iban iban
     *
     * @return string
     */
    public function formatIban($iban)
    {
        if (!is_scalar($iban)) {
            throw new \LogicException('Iban has to be scalar');
        }

        $iban = preg_replace('/\s+/', '', $iban);

        return implode(' ', str_split($iban, 4));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rezzza_accounting_iban';
    }
}
