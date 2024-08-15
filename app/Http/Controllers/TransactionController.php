<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Buscar as transações do usuario Logado
    public function getTransaction()
    {      
        $walletId = Auth::id(); 
                       
        $transactions = Transaction::with(['senderWallet.user', 'receiverWallet.user'])
            ->where(function ($query) use ($walletId) {
                $query->where('sender_wallet_id', $walletId)
                    ->orWhere('receiver_wallet_id', $walletId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($transactions);
    }    
}
