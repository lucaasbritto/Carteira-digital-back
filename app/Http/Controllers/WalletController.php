<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{    
    // Pesquisar todas as carteiras do usuario logado
    public function getWallets(){        
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $wallet = $user->wallet;

        if ($wallet) {
            return response()->json($wallet);
        } else {
            return response()->json(['message' => 'Carteira não encontrada'], 404);
        }        
    }


    // Pesquisar Carteiras dos usuarios para enviar dinheiro
    public function getWalletUsers(){
        $authenticatedUserId = Auth::id(); 

        $wallets = Wallet::select('wallets.id', 'wallets.cod', 'users.name as user_name')
                        ->join('users', 'wallets.user_id', '=', 'users.id')
                        ->where('users.id', '!=', $authenticatedUserId)
                        ->get();
        
        return response()->json($wallets);
    }


    // Buscar uma carteira Especifica
    public function getWallet($id){
        $wallet = Wallet::find($id);

        if (!$wallet) {
            return response()->json(['error' => 'Carteira não encontrada'], 404);
        }

        return response()->json($wallet);
    }

    // Criar uma carteira
    public function store(Request $request){
       
        $request->validate([
            'name' => 'required|string|max:255',
            'pix' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        $wallet = $user->wallet()->create([
            'name' => $request->name,
            'pix' => $request->pix,
            'valor' => $request->valor,
        ]);

        return response()->json([
            'message' => 'Carteira criada com sucesso!',
            'wallet' => $wallet,
        ], 201);
    }

    // Pesquisar se PIX digitado existe
    public function getPersonByPixKey(Request $request) {
        $request->validate([
            'pix_key' => 'required|string'
        ]);

        $result = DB::table('wallets')
                    ->join('users', 'wallets.user_id', '=', 'users.id')
                    ->where('wallets.pix', $request->pix_key)
                    ->select('wallets.name as wallet_name', 'wallets.cod as wallet_cod',  'users.name as user_name')
                    ->first();

        if (!$result) {
            return response()->json(['error' => 'Chave PIX não encontrada'], 404);
        }

        return response()->json([
            'wallet_name' => $result->wallet_name,
            'wallet_cod' => $result->wallet_cod,
            'user_name' => $result->user_name
        ]);
    }

    // Transferir o Dinheiro
    public function transfer(Request $request){        
        $request->validate([
            'sender_wallet_id' => 'required|exists:wallets,id',
            'receiver_wallet_id' => 'required_if:transfer_type,wallet,my_wallet', // Necessário para transferências via carteira
            'receiver_pix_key' => 'required_if:transfer_type,pix', // Necessário para transferências via PIX
            'quantia' => 'required|numeric|min:0.01',
            'transfer_type' => 'required|in:wallet,my_wallet,pix'  // Tipo de transferência
        ]);
        
        $tipo = "";
        $amount = $request->quantia;
        
        $senderWallet = Wallet::where('id', $request->sender_wallet_id)
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$senderWallet) {
            return response()->json(['error' => 'Carteira remetente não encontrada'], 404);
        }

        if ($senderWallet->valor < $amount) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }

        // Se for via Carteira
        if ($request->transfer_type === 'wallet' || $request->transfer_type === 'my_wallet') {
            $receiverWallet = Wallet::findOrFail($request->receiver_wallet_id);  
            
            if (!$receiverWallet) {
                return response()->json(['error' => 'Carteira do destinatário não encontrada'], 404);
            } 

            $tipo = "CARTEIRA";
            
        }

        // Se for Via PIX
        else if ($request->transfer_type === 'pix') {
            $receiverWallet = Wallet::where('pix', $request->receiver_pix_key)->first();
    
            if (!$receiverWallet) {
                return response()->json(['error' => 'Chave PIX do destinatário não encontrada'], 404);
            }   
            $tipo = "PIX";            
        }        

        // Cadastrar a
        DB::transaction(function () use ($senderWallet, $receiverWallet, $amount,$tipo) {
            $senderWallet->decrement('valor', $amount);
            $receiverWallet->increment('valor', $amount);

            Transaction::create([
                'sender_wallet_id' => $senderWallet->id,
                'receiver_wallet_id' => $receiverWallet->id,
                'quantia' => $amount,
                'tipo' => $tipo,
            ]);
        });

        return response()->json(['success' => 'Transferência realizada com sucesso']);
    }

    
}
