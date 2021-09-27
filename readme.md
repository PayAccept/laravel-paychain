# laravel-paychain
Package for receive payments via PayChain

## Prerequisites
**Laravel version >= 5.5**

There is lot of docs about installing Laravel, not covered here ...

**Running Paychain server**

You can find docs here :
https://bitcoin.org/en/full-node#what-is-a-full-node

The Paychain node is based on bitcoin-core-0.16

## Installation

First, make laravel application :
```
laravel new first-paychain-app
```

then  cd in first-bitcoin-app:
```
cd first-paychain-app
```
Install package via composer:
```
composer require payaccept/laravel-paychain
```
After installation publish package with artisan command :
```
php artisan vendor:publish
```
and choose by tag **paychain**.

In config folder you'll find new config file - **paychain.php**
You can change values in file directly, but most common way is to put values in Laravel **.env** file, like this :
```
// Put this at the end of .env file
PAYCHAIN_HOST=127.0.0.1 // change this with host of Paychain server
PAYCHAIN_PORT=8332
PAYCHAIN_USER=rpcusername // change this with rpc user name from bitcoin config
PAYCHAIN_PASSWORD=rpcuserpassword  //change this with rpc passord from bitcoin config
PAYCHAIN_MIN_CONFIRMATIONS=3  // This is minimal number of confirmations - do some google about bitcoin confirmations if you not shure about this value

```

Next, run migrations :
```
php artisan migrate
```


## Usage
There are two main objects in package :

**1. PayAccept\LaravelPaychain\Paychain** - this is a wrapper for RPC commands to bitcoin server.
You can make this object via Laravel **app** helper function like this:
```
$paychain = app("paychain");
// or
$paychain = app("PayAccept\LaravelPaychain\Paychain");
```

and then call RPC method on object :

```
$bitcoinaddress = $paychain->getnewaddress();
// You can call any RPC method
// see this url : https://en.bitcoin.it/wiki/Original_Paychain_client/API_calls_list
```
You can  use just this object for simpler projects  ...

**2. PayAccept\LaravelPaychain\Models\Payment** - this object is model for payment with Paychain , creates for you new Paychain address, amount, track if customer made payment, store it to database ...

Again, use **app** helper function like this:
```
$payment = app("PayAccept\LaravelPaychain\Models\Payment");
//or
$payment = app("bitcoinPayment");

```

This object contains following properties:

**user_id** - id of user who made order

**order_id** - order id (not mandatory)

**address** - bitcoin address (this is automatically generated for you when you call $payment = app("bitcoinPayment"))

**paid** - this is indicator if user is made payment and number of confirmations on block chain is ok (there is configuration parameter for minimum number of confirmations PAYCHAIN_MIN_CONFIRMATIONS in .env file or directly in paychain.conf file in config folder)

**amount** - price that user need to pay

**amount_received** - amount of bitcoin that is actually received (some user can make mistake and send bitcoins with fee deducted from amount send from their wallet, than you wont get expected amount)

**txid** - transaction id in block chain for this payment (this is populated in database when user actually make payment)

**confirmations** - number of confirmations for payment.

### Typical workflow

When order is made by user, you make new Payment object and populate its properties :
```
// Most likely you'll use code like this in some of your Controllers

$payment = app("bitcoinPayment"); // new  bitcoin address is automatically generated
$payment->user_id = $user->id;
$payment->amount = 0.05; // this is price for order or item
$payment->save();
```


#### Checking payments and confirmations:
Package contains class PayAccept\LaravelPaychain\Commands\CheckPayment.
This is Laravel Command and you can call it via php artisan :
```
php artisan bitcoin:checkpayment
```
Each time you call it, it scan for payments and confirmations on block chain. You can call it manually for testing purposes like mentioned above , but there is no much sense to do so, because it's job is to check for payments and it needs to run always.

You need to make crontab entry on linux servers or task scheduler on Win servers to call this command every minute.
```
// Example for linux
crontab -e

// add this line to cron tab and replace path
* * * * * cd /path/to/your/project/ php artisan bitcoin:checkpayment >/dev/null 2>&1
```
This script also fires events that we can listen ...
#### Listening for payments 
You'll find new classes in **app\Listeners** folder of your app when you published pakage ( see installation section).
Those are : 

**ConfirmedPaymentListener.php**

**UnconfirmedPaymentListener.php**

**UnknownTransactionListener.php**

Each of these Listeners correspond to Events which are placed in vendor folder of project :
**vendor\payaccept\laravel-paychain\src\Events**

**ConfirmedPaymentEvent.php**

**UnconfirmedPaymentEvent.php**

**UnknownTransactionEvent.php**

To activate these Listeners copy this code in **app\Providers\EventServiceProvider.php** class (this class exists by default installation of Laravel), in **$listen** attribute of this class.

Like this:
```
protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        'PayAccept\LaravelPaychain\Events\ConfirmedPaymentEvent' => [
            'App\Listeners\ConfirmedPaymentListener',
        ],

        'PayAccept\LaravelPaychain\Events\UnconfirmedPaymentEvent' => [
            'App\Listeners\UnconfirmedPaymentListener',
        ],

        'PayAccept\LaravelPaychain\Events\UnknownTransactionEvent' => [
            'App\Listeners\UnknownTransactionListener',
        ],

    ];
```
In each of these class there is handle method, where you can put logic for actions that need to be done when event is fired (DB insert-update, sending mails ...).

Below is example of ConfimedPaymentListener,  event is generated when number of confirmations is equal to PAYCHAIN_MIN_CONFIRMATIONS in .env file and we can be sure that payment is ok.
```
    public function handle(ConfirmedPaymentEvent $event)
    {
        Log::debug('Confirmed Payment listener: '. $event->confirmedPayment);
         // Here you add your code for sending mails, db update ...
    }
```

### Events

1. **PayAccept\LaravelPaychain\Events\UnconfirmedPaymentEvent**  - Payment is made by user.
Transaction id is generated on block chain, number of conifrmation on block chain  is 0 - so you have to wait for additional confirmations.

2. **PayAccept\LaravelPaychain\Events\ConfirmedPaymentEvent** - Payment is made and number of confirmations is equal or greater than value  of **PAYCHAIN_MIN_CONFIRMATIONS** in .env file.


3. **PayAccept\LaravelPaychain\Events\UnknownTransactionEvent** - Usually this happens when user make payment and transaction fee is deducted from amount that he sends from his wallet  and you don't receive whole amount. This payments are stored in separate table and you can make logic how to resolve situation like this. 


### Models
**PayAccept\LaravelPaychain\Models\Payment** - Represents Confirmed and Unconfirmed Payments (see Usage section)

**PayAccept\LaravelPaychain\Models\UnknownTransaction** - Represent transaction on block chain with amount that does not correspond to amount in Payment model.

## License
MIT License
