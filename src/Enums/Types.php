<?php

namespace MimoGraphix\QIF\Enums;

/**
 * Class HeaderLines
 *
 * @author MimoGraphix <mimographix@gmail.com>
 * @package MimoGraphix\QIF\Enums
 */
class Types
{
    const CASH = 'Cash';        // Cash Flow: Cash Account
	const BANK = 'Bank';        // Cash Flow: Checking & Savings Account
	const CCARD = 'CCard';      // Cash Flow: Credit Card Account
	const INVST = 'Invst';      // Investing: Investment Account
	const OTHA = 'Oth A';       // A	Property & Debt: Asset
	const OTHL = 'Oth L';       // L	Property & Debt: Liability
	const Invoice = 'Invoice';  // Invoice (Quicken for Business only)
}