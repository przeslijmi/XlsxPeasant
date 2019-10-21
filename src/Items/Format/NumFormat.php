<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items\Format;

use Przeslijmi\XlsxPeasant\Items\Format;

/**
 * Number format definition used in Style.
 */
class NumFormat extends Format
{

    /**
     * Number of decimal places.
     *
     * @var integer
     */
    private $decimalPlaces = 0;

    /**
     * Number of leading zeros.
     *
     * @var integer
     */
    private $leadingZeros = 0;

    /**
     * Unit for number.
     *
     * @var string
     */
    private $unit = '';

    /**
     * Construct.
     *
     * @param integer $decimalPlaces Optional, 0. Number of decimal places.
     * @param integer $leadingZeros  Optional, 0. Number of leading zeros.
     * @param string  $unit          Optional, none. Unit for number.
     *
     * @since v1.0
     */
    public function __construct(int $decimalPlaces = 0, int $leadingZeros = 0, string $unit = '')
    {

        $this->setType('number');
        $this->setDecimalPlaces($decimalPlaces);
        $this->setLeadingZeros($leadingZeros);
        $this->setUnit($unit);
    }

    /**
     * Setter for decimalPlaces.
     *
     * @param integer $decimalPlaces Number of decimal places.
     *
     * @since  v1.0
     * @return self
     */
    public function setDecimalPlaces(int $decimalPlaces) : self
    {

        $this->decimalPlaces = $decimalPlaces;

        return $this;
    }

    /**
     * Getter for decimalPlaces.
     *
     * @since  v1.0
     * @return integer
     */
    public function getDecimalPlaces() : int
    {

        return $this->decimalPlaces;
    }

    /**
     * Setter for leadingZeros.
     *
     * @param integer $leadingZeros Number of leading zeros.
     *
     * @since  v1.0
     * @return self
     */
    public function setLeadingZeros(int $leadingZeros) : self
    {

        $this->leadingZeros = $leadingZeros;

        return $this;
    }

    /**
     * Getter for leadingZeros.
     *
     * @since  v1.0
     * @return integer
     */
    public function getLeadingZeros() : int
    {

        return $this->leadingZeros;
    }

    /**
     * Setter for unit.
     *
     * @param string $unit Number of leading zeros.
     *
     * @since  v1.0
     * @return self
     */
    public function setUnit(string $unit) : self
    {

        $this->unit = $unit;

        return $this;
    }

    /**
     * Getter for unit.
     *
     * @since  v1.0
     * @return null|string
     */
    public function getUnit() : ?string
    {

        return $this->unit;
    }

    /**
     * Return code of Format in XLSX language (syntax).
     *
     * @since  v1.0
     * @return string
     */
    public function getCode() : string
    {

        // Lvd.
        $unit          = '&quot;' . $this->getUnit() . '&quot;';
        $dp            = $this->getDecimalPlaces();
        $decimalPlaces = ( ( $dp > 0 ) ? '.' . str_repeat('0', $dp) : '' );

        $mtZero = '#,##0' . $decimalPlaces . '\ ' . $unit . ' ';
        $ltZero = '-' . $mtZero;

        return $mtZero . ';' . $ltZero;
    }

    /**
     * Getter for signature.
     *
     * @since  v1.0
     * @return string
     */
    public function getSignature() : string
    {

        // Lvd.
        $result = '';

        // Fill up.
        $result .= 'type:' . $this->getType();
        $result .= 'decimalPlaces:' . (string) $this->getDecimalPlaces();
        $result .= 'leadingZeros:' . (string) $this->getLeadingZeros();
        $result .= 'unit:' . $this->getUnit();

        return $result;
    }
}
