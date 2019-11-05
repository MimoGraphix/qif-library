<?php

namespace MimoGraphix\QIF;

use MimoGraphix\QIF\Enums\DetailItems;
use Carbon\Carbon;

/**
 * Class Parser
 *
 * @author MimoGraphix <mimographix@gmail.com>
 * @package MimoGraphix\QIF
 */
class Parser
{
    /**
     * @var string
     */
    private $fileContent;

    /**
     * @var string
     */
    private $separator;

    /**
     * @var array
     */
    private $transactions = [];

    public function __construct( $fileContent, $separator = "\r\n" )
    {
        $this->fileContent = $fileContent;
        $this->separator = $separator;
    }

    /**
     * @param $filePath
     * @return Parser
     */
    public static function parseFile( $filePath )
    {
        $parser = new self( file_get_contents( $filePath ) );
        $parser->parse();
        return $parser;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    public function parse()
    {
        $line = strtok( preg_replace( "/\xEF\xBB\xBF/", "", $this->fileContent ), $this->separator );

        $lastType = null;
        $transaction = new Transaction( $lastType );
        $transactionRaw = "";
        while ( $line !== false )
        {
            $line = trim( $line );
            $transactionRaw .= $line . $this->separator;

            $first = substr( $line, 0, 1 );
            $line = substr( $line, 1 );
            switch ( $first )
            {
                case '!':   // Type
                    $lastType = trim( str_replace( "Type:", "", $line ) );
                    $transaction = new Transaction( $lastType );
                    $transactionRaw = "!Type:" . $lastType;
                    break;
                case DetailItems::D:
                    $transaction->setDate( Carbon::parse( str_replace( "'", ".", $line ) ) );
                    break;
                case DetailItems::T:
                case DetailItems::U:
                    $transaction->setAmount( (float) str_replace( ",", "", $line ) );
                    break;
                case DetailItems::M:
                    $transaction->setMemo( $line );
                    break;
                case DetailItems::C:
                    $transaction->setStatus( $line );
                    break;
                case DetailItems::P:
                    $transaction->setDescription( $line );
                    break;
                case DetailItems::A:
                    $transaction->setAddress( $line );
                    break;
                case DetailItems::O:
                    break;
                case DetailItems::L:
                    $transaction->setCategory( $line );
                    break;
                case "^":
                    $transaction->_raw = $transactionRaw;
                    $this->transactions[] = $transaction;
                    $transaction = new Transaction( $lastType );
                    $transactionRaw = "!Type:" . $lastType;
                    break;
            }

            $line = strtok( $this->separator );
        }

        return $this->getTransactions();
    }
}