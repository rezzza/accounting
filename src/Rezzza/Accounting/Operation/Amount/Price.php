<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Amount;

use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\Operation;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Price extends AbstractOperand implements OperandInterface
{
    /**
     * @var string
     */
    protected $currency = 'EUR';

    /**
     * @var integer
     */
    private $fractionDigits = 2;

    /**
     * @var formatter
     */
    private $formatter = null;

    /**
     * @param float  $value   value
     * @param string $currency currency
     */
    public function __construct($value, $currency = 'EUR', $formatter = null)
    {
        $this->value          = (float) $value;
        $this->currency       = (string) $currency;
        $this->fractionDigits = Intl::getCurrencyBundle()->getFractionDigits($currency);

        if ($formatter) {
            $this->setFormatter($formatter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getFormatter()->formatCurrency($this->getValue(), $this->getCurrency());
    }

    public function getFormatter()
    {
        if (null === $this->formatter) {
            if (!Intl::isExtensionLoaded()) {
                // This number formatter don't have exactly the same behavior
                $this->formatter = new NumberFormatter('en', NumberFormatter::CURRENCY);
            } else {
                $this->formatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY);
            }
        }

        return $this->formatter;
    }

    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @return integer
     */
    public function getFractionDigits()
    {
        return $this->fractionDigits;
    }

    /**
     * {@inheritdoc}
     */
    public function compute($operation, OperandInterface $right)
    {
        if ($right instanceof Price) {
            return $this->computeWithPrice($operation, $right);
        } elseif ($right instanceof Percentage) {
            return $this->computeWithPercent($operation, $right);
        } else {
            throw new \LogicException(sprintf('Unsupported operand (%s). Percentage operand can only be computed with Percentage or Price operands.', get_class($right)));
        }
    }

    /**
     * @param string $operation operation
     * @param Price  $right     right
     *
     * @return Result
     */
    protected function computeWithPrice($operation, Price $right)
    {
        switch($operation) {
            case Operation::MAJORATE:
                $value = $this->round($this->getValue() + $right->getValue());
                break;
            case Operation::MINORATE:
                $value = $this->round($this->getValue() - $right->getValue());
                break;
            default:
                throw new \LogicException(sprintf('Unsupported operator (%s) in operation: "%s %s %s".', $operation, $this, $operation, $right));
                break;
        }

        return new Result(
            new Price($value, $this->getCurrency(), $this->getFormatter()),
            new Price($this->round($this->getValue() - $value), $this->getCurrency(), $this->getFormatter()),
            sprintf('%s %s %s', $this, $operation, $right)
        );
    }

    /**
     * @param string     $operation operation
     * @param Percentage $right     right
     *
     * @return Result
     */
    protected function computeWithPercent($operation, Percentage $right)
    {
        switch($operation) {
            case Operation::MAJORATE:
                $value = $this->round( $this->getValue() * (1 + ($right->getValue() / 100)) );
                break;
            case Operation::MINORATE:
                $value = $this->round( $this->getValue() * (1 - ($right->getValue() / 100)) );
                break;
            case Operation::EXONERATE:
                $value = $this->round( $this->getValue() / (1 + ($right->getValue() / 100)) );
                break;
            default:
                throw new \LogicException(sprintf('Unsupported operator (%s) in operation: "%s %s %s".', $operation, $this, $operation, $right));
                break;
        }

        return new Result(
            new Price($value, $this->getCurrency(), $this->getFormatter()),
            new Price($this->round(abs($this->getValue() - $value)), $this->getCurrency(), $this->getFormatter()),
            sprintf('%s %s %s', $this, $operation, $right)
        );
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    private function round($value)
    {
        return (float) round($value, $this->getFractionDigits(), PHP_ROUND_HALF_UP);
    }
}
