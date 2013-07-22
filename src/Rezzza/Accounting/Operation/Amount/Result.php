<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Amount;

use Rezzza\Accounting\Operation\OperandInterface;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Result extends Price implements OperandInterface
{
    /**
     * @var float
     */
    protected $complement;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @param OperandInterface $value      value
     * @param OperandInterface $complement complement
     * @param string           $operation     operation
     */
    public function __construct(OperandInterface $value, OperandInterface $complement, $operation)
    {
        $this->value      = $value;
        $this->complement = $complement;
        $this->operation  = $operation;
    }

    public function __toString()
    {
        return sprintf('%s => %s \ %s', $this->operation, $this->value, $this->complement);
    }

    /**
     * @return float
     */
    public function getComplement()
    {
        return $this->complement;
    }
}
