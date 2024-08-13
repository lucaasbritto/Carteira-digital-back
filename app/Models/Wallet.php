<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'valor'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        // Fazendo relação com Transação
        return $this->hasMany(Transaction::class, 'sender_wallet_id');
    }
}

