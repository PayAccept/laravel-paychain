<?php

namespace App\Listeners;

use PayAccept\LaravelPaychain\Events\UnconfirmedPaymentEvent;
use Illuminate\Support\Facades\Log;

class UnconfirmedPaymentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UnconfirmedPaymentEvent  $event
     * @return void
     */
    public function handle(UnconfirmedPaymentEvent $event)
    {
        Log::debug('Unconfirmed Payment listener: '. $event->unconfirmedPayment);
    }
}
