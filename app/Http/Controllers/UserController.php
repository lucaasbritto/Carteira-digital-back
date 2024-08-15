<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUserId()
    {
        // Retorna o ID do usuário autenticado
        return response()->json(['user_id' => Auth::id()]);
    }
}
