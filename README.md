# PHP QIF Library

A simple QIF parsing/writing library.

## Installation

```
composer require mimographix/qif-library
```

## Usage

### Parser

```php
// Instatiate the QIF Parser
$qifParser = new MimoGraphix\QIF\Parser( $filePath );
$qifParser->parse();

foreach( $qifParser->getTransactions() as $transaction )
{
    // your code
}
```

### Writer
```php
// Instatiate the QIF Writer
$qif = new MimoGraphix\QIF\Writer();

// Create a new transaction
$transaction = new Transaction( Enums\Types::CASH );

$transaction->setDate( new Carbon( '2019-12-31' ) )
	->setDescription( 'INV666: ' )
	->setAmount( 18.99 )
	->setCategory( 'Sales' )
	->addSplit( 'Sales', 18 )
	->addSplit( 'Tax', 0.99 )
	->markAsReconciled();

// Add it to the QIF
$qif->addTransaction( $transaction );

echo $qif;
```