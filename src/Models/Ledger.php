<?php

namespace Scottlaurent\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Money\Money;
use Money\Currency;
use Carbon\Carbon;

/**
 * Class Journal
 * @package Scottlaurent\Accounting
 * @property    Money                  $balance
 * @property    string                 $currency
 * @property    Carbon                 $updated_at
 * @property    Carbon                 $post_date
 * @property    Carbon                 $created_at
 */
class Ledger extends Model
{

    /**
     * @var string
     */
	protected $table = 'accounting_ledgers';

    /**
     * Currency.
     *
     * @var string $currency
     */
	public $currency;

    /**
	 *
	 */
	public function journals()
	{
		return $this->hasMany(Journal::class);
	}
	
	/**
     * Get all of the posts for the country.
     */
    public function journal_transactions()
    {
        return $this->hasManyThrough(JournalTransaction::class, Journal::class);
    }
	
	/**
	 *
	 */
	public function getCurrentBalance()
	{
		if ($this->type == 'asset' || $this->type == 'expense') {
			$balance = $this->journal_transactions->sum('debit') - $this->journal_transactions->sum('credit');
		} else {
			$balance = $this->journal_transactions->sum('credit') - $this->journal_transactions->sum('debit');
		}
		
		return new Money($balance, new Currency($this->currency));
	}
	
		/**
	 *
	 */
	public function getCurrentBalanceInDollars()
	{
		return $this->getCurrentBalance()->getAmount() / 100;
	}
	
	
}