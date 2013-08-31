<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Wrapper;

use Rezzza\Accounting\Operation\Amount\Result;
use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\OperationInterface;
use Rezzza\Accounting\Operation\OperationSetResult;
use Rezzza\Accounting\Operation\Reference\ReferenceInterface;

/**
 * Operand
 *
 * @uses OperationInterface
 * @uses OperandInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Operand implements OperationInterface
{
    CONST VALUE      = 'value';
    CONST COMPLEMENT = 'complement';

    /**
     * @var string
     */
    protected $field;

    /**
     * @var OperandInterface
     */
    protected $operand;

    /**
     * @param string                    $field   field
     * @param Result|ReferenceInterface $operand operand
     */
    public function __construct($field, $operand)
    {
        $availableFields = array(self::VALUE, self::COMPLEMENT);

        if (!in_array($field, $availableFields)) {
            throw new \InvalidArgumentException(sprintf('Unsupported Result attribute (%s). Please use one of these: %s.', $field, implode(', ', $availableFields)));
        }

        if (!$operand instanceof Result && !$operand instanceof ReferenceInterface) {
            throw new \InvalidArgumentException('Class Operation\Wrapper\Operand accepts a Operation\Amount\Result or a Operation\Reference\ReferenceInterface');
        }

        $this->field   = $field;
        $this->operand = $operand;
    }

    /**
     * {@inheritdoc}
     *
     * If operand is a Reference with extract it.
     * It accepts only a Result instance since it is the only one result it can wrap.
     */
    public function compute()
    {
        if ($this->operand instanceof ReferenceInterface) {
            $this->operand = $this->operand->getReference();

            if (!$this->operand) {
                throw new \LogicException(sprintf('Operand can not be found in results set (%s).', $this->operand));
            }
        }

        if (!$this->operand instanceof Result) {
            throw new \LogicException('Wrapper can wrap only a Amount\Result instance.');
        }

        $value = $this->field === self::VALUE ? $this->operand->getValue() : $this->operand->getComplement();

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function needsResultsSet()
    {
        return $this->operand instanceof ReferenceInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function setResultsSet(OperationSetResult $resultsSet)
    {
        $this->operand->setResultsSet($resultsSet);
    }
}
