<?php

namespace PayAccept\LaravelPaychain\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
   public function order()
   {
       return $this->belongsTo(config('paychain.order-model'));
   }

   public function user()
   {
       return $this->belongsTo(config('paychain.user-model'));
   }

   public function scopeUnpaid($query)
   {
       return $query->where('txid', '=','');
   }

   public function scopeNot_confirmed($query)
   {
       return $query->where('txid','!=','')
                    ->where('paid','=', 0);

   }

   public function scopePaid($query)
   {
       return $query->where('paid','=', 1);
   }
}
