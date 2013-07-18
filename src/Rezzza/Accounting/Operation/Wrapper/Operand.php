<?php

namespace Rezzza\Accounting\Operation\Wrapper;

use Rezzza\Accounting\Operation\Amount\Price;
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
class Operand implements OperationInterface, OperandInterface
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
     * @param string           $field   field
     * @param OperandInterface $operand operand
     */
    public function __construct($field, OperandInterface $operand)
    {
        $availableFields = array(self::VALUE, self::COMPLEMENT);

        if (!in_array($field, $availableFields)) {
            throw new \InvalidArgumentException(sprintf('Field "%s" is not supported, please use one of theses: %s', $field, implode(', ', $availableFields)));
        }

        $this->field   = $field;
        $this->operand = $operand;
    }

    /**
     * compute the wrapper.
     *
     * If operand is a Reference with extract it.
     * It accepts only a Result instance since it is the only one result it can wrap.
     *
     * @return void
     */
    public function compute()
    {
        if ($this->operand instanceof ReferenceInterface) {
            $this->operand = $this->operand->getReference();

            if (!$this->operand) {
                throw new \LogicException('Operand cant be found in results set.');
            }
        }

        if (!$this->operand instanceof Result) {
            throw new \LogicException('Wrapper can wrap only a Amount\Result instance.');
        }

        $value = $this->field === self::VALUE ? $this->operand->getValue() : $this->operand->getComplement();

        return new Price($value, $this->operand->getCurrency());
    }

    /**
     * @return boolean
     */
    public function needsResultsSet()
    {
        return $this->operand instanceof ReferenceInterface;
    }

    /**
     * @param OperationSetResult $resultsSet resultsSet
     */
    public function setResultsSet(OperationSetResult $resultsSet)
    {
        $this->operand->setResultsSet($resultsSet);
    }
}
