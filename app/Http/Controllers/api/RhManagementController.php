<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
class RhManagementController extends Controller
{
    public function index()
    {
        Auth::user()->can('rh') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $colaborators = User::with('detail', 'department')->where('role', 'colaborator')->get();
        return view('colaborators.colaborators', compact('colaborators'));
    }
}
