<?php

// Define the namespace
namespace App\Support\Payments;

// Include any required classes, interfaces etc...
use DB;
use Carbon\Carbon;
use App\Model\User\User;
use App\Models\Payments\Payment;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;

/**
 * Payment Manager
 *
 * @author      Ivan Ivanov <ivan@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class PaymentManager
{
	/**
	 * The user making the payment.
	 *
	 * @var string
	 */
    protected $payer;

	/**
	 * The driver used to validate the payment.
	 *
	 * @var string
	 */
    protected $driver;

	/**
	 * PaymentManager Constructor.
	 *
	 * @param   User $payer
	 * @param   PaymentDriver $driver
	 */
    public function __construct(User $payer, PaymentDriver $driver)
    {
        $this->payer = $payer;
        $this->driver = $driver;
    }

	/**
	 * Check to ensure the receipt is valid.
	 *
	 * @throws AppException
	 * @return boolean
	 */
    public function check()
    {
        if (!$this->driver->isValidReceipt()) {
            throw new AppException(AppExceptionType::$INVALID_RECEIPT);
        }
	    
	    $original_transaction_id = $this->driver->getTransactionUid(true);

	    $payment = Payment::where('original_provider_uid', $original_transaction_id)->first();

	    if ($payment && $payment->user_id != $this->payer->id) {
		    throw new AppException(AppExceptionType::$DUPLICATE_SUBSCRIPTION);
	    }

	    $transaction_id = $this->driver->getTransactionUid();

	    $consumed = (bool) Payment::where('provider_uid', $transaction_id)->count();
	    
	    if ($consumed) {
		    throw new AppException(AppExceptionType::$PURCHASE_CONSUMED);
	    }

	    $expiration_date = new Carbon($this->driver->getExpirationDate());

	    if (Carbon::now() >= $expiration_date) {
		    
		    $this->payer->downgrade();
		    
		    throw new AppException(AppExceptionType::$TRANSACTION_EXPIRED);
	    }

	    DB::transaction(function() use ($original_transaction_id, $transaction_id, $expiration_date) {

		    $payment                            = new Payment();
		    $payment->user_id                   = $this->payer->id;
		    $payment->provider                  = $this->driver->getProviderSlug();
		    $payment->provider_uid              = $transaction_id;
		    $payment->original_provider_uid     = $original_transaction_id;
		    $payment->receipt                   = $this->driver->getRawReceipt();

		    $payment->save();

		    $this->payer->upgrade($expiration_date);
	    });

	    return true;
    }
}