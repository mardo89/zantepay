<?php

namespace App\Models\Wallet;


class CurrencyFormatter
{
    /**
     * @var mixed Value to format
     */
    protected $originalValue;

    /**
     * @var string Formatted value
     */
    protected $formattedValue;

    /**
     * CurrencyFormatter constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $this->originalValue = $value;
    }

    /**
     * Format value as ETH
     *
     * @return $this
     */
    public function ethFormat() {
        $this->formattedValue = rtrim(sprintf('%.9f', $this->originalValue), '0');

        if (substr($this->formattedValue, -1) == '.') {
            $this->formattedValue = substr($this->formattedValue, 0, -1);
        }

        return $this;
    }

    /**
     * Format value as ZNX
     *
     * @return $this
     */
    public function znxFormat() {
        $this->formattedValue = sprintf('%d', $this->originalValue);

        return $this;
    }

    /**
     * Format value as Number
     *
     * @return $this
     */
    public function numberFormat() {
        $this->formattedValue = number_format($this->originalValue, 0, '.', ' ');

        return $this;
    }

    /**
     * Add prefix
     *
     * @param string $prefix
     *
     * @return $this
     */
    public function withPrefix($prefix) {
        $this->formattedValue = $prefix . ' ' . $this->formattedValue;

        return $this;
    }

    /**
     * Add suffix
     *
     * @param string $sufix
     *
     * @return $this
     */
    public function withSuffix($sufix) {
        $this->formattedValue .= ' ' . $sufix;

        return $this;
    }

    /**
     * Get formatted value
     *
     *
     * @return sting
     */
    public function get() {
        return $this->formattedValue;
    }

}
