<?php

namespace MimoGraphix\QIF;

use Carbon\Carbon;
use MimoGraphix\QIF\Enums\DetailItems;

/**
 * Class Transaction
 *
 * @author MimoGraphix <mimographix@gmail.com>
 * @package MimoGraphix\QIF
 */
class Transaction
{
    /**
     * @var string from Enums\HeaderLines
     */
    private $type = null;

    /**
     * @var Carbon
     */
    private $date = null;

    private $description = null;

    /**
     * @var double|null
     */
    private $amount = null;

    private $category = null;

    /**
     * @var array
     */
    private $splits = [];

    private $status = '';

    private $address = null;

    private $memo = null;

    /**
     * Used in parser to capture original values
     * @var null
     */
    public $_raw = null;

    public function __construct( $type )
    {
        $this->type = $type;
    }

    public function setDate( Carbon $date )
    {
        $this->date = $date;
        return $this;
    }

    public function setDescription( $description )
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param double $float
     * @return $this
     */
    public function setAmount( $float )
    {
        $this->amount = floatval( $float );
        return $this;
    }

    public function setCategory( $category )
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string $splitName
     * @param float $amount
     * @param string|null $memo
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addSplit( $splitName, $amount, $memo = null )
    {
        if ( array_key_exists( $splitName, $this->splits ) )
        {
            throw new \Exception( sprintf( 'Split "%s" already exists in this transaction.', $splitName ) );
        }

        $this->splits[ $splitName ] = [
            DetailItems::AMNT => floatval( $amount ),
            DetailItems::E => $memo,
        ];
        return $this;
    }

    public function removeSplit( $splitName )
    {
        unset( $this->splits[ $splitName ] );
        return $this;
    }

    public function setStatus( $status )
    {
        $this->status = $status;
        return $this;
    }

    public function markAsReconciled()
    {
        $this->status = 'X';
        return $this;
    }

    public function markAsCleared()
    {
        $this->status = 'c';
        return $this;
    }

    public function markAsNotCleared()
    {
        $this->status = '';
        return $this;
    }

    public function __toString()
    {
        $output = [
            "!Type:" . $this->type,
            $this->renderDateLineIfNotNull(),
            $this->renderIfNotNull( DetailItems::T, $this->amount ),
            $this->renderIfNotNull( DetailItems::L, $this->category ),
            $this->renderSplits(),
            $this->renderIfNotNull( DetailItems::C, $this->status ),
            $this->renderIfNotNull( DetailItems::P, $this->description ),
            '^',
        ];

        return implode( PHP_EOL, array_filter( $output ) );
    }

    /**
     * @return string|false
     */
    private function renderDateLineIfNotNull()
    {
        if ( $this->date instanceof \DateTime )
            return $this->renderIfNotNull( DetailItems::D, $this->date->format( 'd/m/Y' ) );

        return false;
    }

    /**
     * @param $characterKey
     * @param null $value
     * @return string|false
     */
    private function renderIfNotNull( $characterKey, $value = null )
    {
        if ( !is_null( $value ) )
            return $characterKey.$value;

        return false;
    }

    private function renderSplits()
    {
        $output = [];
        foreach ( (array) $this->splits as $name => $split )
        {
            $output[] = $this->renderIfNotNull( DetailItems::S, $name );
            $output[] = $this->renderIfNotNull( DetailItems::AMNT, $split[ DetailItems::AMNT ] );
            $output[] = $this->renderIfNotNull( DetailItems::E, DetailItems::E );
        }

        return implode( PHP_EOL, array_filter( $output ) );
    }

    public function setAddress( $address )
    {
        $this->address = $address;
        return $this;
    }

    public function setMemo( $memo )
    {
        $this->memo = $memo;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Carbon|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return double|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return array
     */
    public function getSplits()
    {
        return $this->splits;
    }

    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getMemo()
    {
        return $this->memo;
    }
}
