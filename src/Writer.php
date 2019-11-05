<?php

namespace MimoGraphix\QIF;

/**
 * Class Writer
 *
 * @author MimoGraphix <mimographix@gmail.com>
 * @package MimoGraphix\QIF
 */
class Writer
{
    /**
     * @var Transaction[]
     */
    private $transactions = [];

    /**
     * @param Transaction $transaction
     */
    public function addTransaction( Transaction $transaction )
    {
        $this->transactions[] = $transaction;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    public function __toString()
    {
        $output = [];

        foreach ( $this->transactions as $transaction )
        {
            $output[] = (string) $transaction;
        }

        return implode( PHP_EOL, array_filter( $output ) );
    }

    /**
     * @param $filePath
     */
    public function saveToFile( $filePath )
    {
        $file = fopen( $filePath, "w" ) or die( "Unable to open file!" );
        fwrite( $file, (string) $this );
        fclose( $file );
    }

}
