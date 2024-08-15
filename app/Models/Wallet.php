<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['cod','user_id', 'name', 'pix', 'valor'];

    protected static function booted()
    {
        static::creating(function ($wallet) {
            // Gera um código de 4 dígitos único
            $wallet->cod = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        });
    }

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

