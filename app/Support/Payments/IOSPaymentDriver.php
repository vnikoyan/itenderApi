<?php

// Define the namespace
namespace App\Support\Payments;

// Include any required classes, interfaces etc...
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;

/**
 * iOS Payment Driver
 *
 * @author      Ivan Ivanov <ivan@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class IOSPaymentDriver implements PaymentDriver
{
	/**
	 * Set the live mode constant.
	 *
	 * @var string
	 */
	const MODE_LIVE = 'live';

	/**
	 * Set the sandbox mode constant.
	 *
	 * @var string
	 */
	const MODE_SANDBOX = 'sandbox';

	/**
	 * Set the live mode url constant.
	 *
	 * @var string
	 */
	const URL_LIVE = 'https://buy.itunes.apple.com/verifyReceipt';

	/**
	 * Set the sandbox mode url constant.
	 *
	 * @var string
	 */
	const URL_SANDBOX = 'https://sandbox.itunes.apple.com/verifyReceipt';

    /**
     * Shows if the given transaction is valid.
     *
     * @var bool
     */
    protected $isValid = null;

    /**
     * The receipt data.
     *
     * @var string
     */
    protected $receiptData;

    /**
     * The response object.
     *
     * @var \stdClass
     */
    protected $response;

    /**
     * The transaction id validated.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * The validated transaction object.
     *
     * @var \stdClass
     */
    protected $transactionData;

	/**
	 * The application mode.
	 *
	 * @var string
	 */
	protected $mode;

	/**
	 * The application password.
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * IOSPaymentDriver Constructor.
	 *
	 * @param   string $receipt_data
	 * @param   string $transaction_id
	 * @param   string $mode
	 * @param   string $password
	 */
    public function __construct($receipt_data, $transaction_id, $mode = self::MODE_LIVE, $password = '')
    {
        $this->receiptData      = $receipt_data;
        $this->transactionId    = $transaction_id;
	    $this->mode             = $mode;
	    $this->password         = $password;
    }

	/**
	 * Determine if supplied receipt is valid.
	 *
	 * @return boolean
	 */
    public function isValidReceipt() : bool
    {
        if ($this->isValid === null) {
            $this->isValid = $this->validateReceiptData();
        }

        return $this->isValid;
    }

	/**
	 * Get the product id from the transaction.
	 *
	 * @throws AppException
	 * @return string
	 */
    public function getProductIdentifier() : string
    {
        if(!$this->isValidReceipt()) {
            throw new AppException(AppExceptionType::$INVALID_RECEIPT);
        }
        return "1";

//        return $this->transactionData->product_id ?? null;
    }

	/**
	 * Get the original transaction id from the transaction.
	 *
	 * @param   boolean $original
	 * @throws  AppException
	 * @return  string
	 */
	public function getTransactionUid($original = false) : string
	{
		if(!$this->isValidReceipt()) {
			throw new AppException(AppExceptionType::$INVALID_RECEIPT);
		}
        return "1";
//		return $original ? ($this->transactionData->original_transaction_id ?? null) : ($this->transactionData->transaction_id ?? null);
	}

	/**
	 * Get the provider slug.
	 *
	 * @return string
	 */
	public function getProviderSlug() : string
	{
		return 'apple';
	}

	/**
	 * Get the raw receipt string.
	 *
	 * @return string
	 */
	public function getRawReceipt() : string
	{
		return $this->receiptData;
	}

	/**
	 * Get the expiration date.
	 *
	 * @throws  AppException
	 * @return  string
	 */
	public function getExpirationDate(): string
	{
		if(!$this->isValidReceipt()) {
			throw new AppException(AppExceptionType::$INVALID_RECEIPT);
		}

		return $this->transactionData->expires_date;
	}

	/**
	 * Validate the supplied receipt data.
	 *
	 * @return boolean
	 */
    private function validateReceiptData() : bool
    {
        $this->response = $this->getReceiptData();

        if ($this->response->status > 0) {
            return false;
        }

        $this->transactionData = $this->getTransactionData();

        if (!$this->transactionData) {
            return false;
        }

        return true;
    }

	/**
	 * Get the receipt data from the supplied receipt.
	 *
	 * @return array
	 */
    private function getReceiptData()
    {
        $client = new \GuzzleHttp\Client();
        $data   = [
        	'json' => [
		        'receipt-data'  => $this->receiptData,
	            'password'      => $this->password
	        ]
        ];

        if ($this->mode == self::MODE_LIVE) {
	        $response = $client->request('POST', self::URL_LIVE, $data);
        } else {
	        $response = $client->request('POST', self::URL_SANDBOX, $data);
        }

        return json_decode($response->getBody()->getContents());
    }

	/**
	 * Get the transaction data from the supplied receipt.
	 *
	 * @return array
	 */
    private function getTransactionData()
    {
    	$transactions = [];

        foreach ($this->response->receipt->in_app as $transaction) {
            if ($transaction->original_transaction_id == $this->transactionId) {
                $transactions[] = $transaction;
            }
        }

        usort($transactions, function($a, $b) {
	        return $a->expires_date_ms < $b->expires_date_ms ? 1 : -1;
        });

        return isset($transactions[0]) ? $transactions[0] : null;
    }
}