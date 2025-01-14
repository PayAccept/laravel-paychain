<?php

namespace PayAccept\LaravelPaychain\Events;

use PayAccept\LaravelPaychain\Models\Payment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ConfirmedPaymentEvent
{
    use SerializesModels;

    public $confirmedPayment;

    /**
     * Fired when num of confirmations on block chain meet PAYCHAIN_MIN_CONFIRMATIONS in .env file.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Payment $confirmedPayment)
    {
        $this->confirmedPayment = $confirmedPayment;
        //Log::debug('ConfirmedPaymentEvent constructor :'.$this->confirmedPayment);
    }
}
