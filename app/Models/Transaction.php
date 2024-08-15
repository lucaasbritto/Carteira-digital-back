<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['sender_wallet_id', 'receiver_wallet_id', 'quantia', 'tipo'];

    public function senderWallet()
    {
        // Relaçao para onde sera enviado
        return $this->belongsTo(Wallet::class, 'sender_wallet_id');
    }

    public function receiverWallet()
    {
        // Relaçao onde esta recebendo
        return $this->belongsTo(Wallet::class, 'receiver_wallet_id');
    }
}

