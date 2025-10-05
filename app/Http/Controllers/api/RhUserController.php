<?php

namespace App\Http\Controllers\Api;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use illuminate\Support\Str;
use App\Http\Controllers\Controller;
class RhUserController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $colaborators = User::with('detail')->where('role', 'rh')->orderBy('name', 'asc')->get(); // inclui os dados da tabela detalhes
        return view('colaborators.rh-users', compact('colaborators'));
    }

    public function newRhColaborator()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $departments = Department::all();
        return view('colaborators.add-rh-user', compact('departments'));
    }

    public function createRhColaborator(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // tabela dos users na coluna email
            'select_department' => 'required|exists:departments,id', // tem que existir na tabela departments e na coluna id (nunca pode ter espaços entre os argumentos)
            'address' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'city' => 'required|string|max:50',
            'phone' => 'required|string|max:50',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d'
        ]);

        // validar se o department_id = 2 (RH)
        if ($request->select_department != 2) {
            return redirect()->route('home');
        }

        $token = Str::random(60);

        // gravar os dados do usuário
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->confirmation_token = $token;
        $user->role = 'rh';
        $user->department_id = $request->select_department;
        $user->permissions = '["rh"]';
        $user->save();

        // // gravar os dados do detalhe do usuário
        $user->detail()->create([
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'salary' => $request->salary,
            'admission_date' => $request->admission_date
        ]);

        // enviar o email
        // Password::sendResetLink(['email' => $user->email]); // este código funciona para enviar email - já testei

        Mail::to($user->email)->send(new ConfirmAccountEmail(route('confirm-account', $token)));

        return redirect()->route('colaborators.rh-users')->with('success', 'Colaborador criado com sucesso!');
    }

    public function editRhColaborator($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $colaborator = User::with('detail')->where('role', 'rh')->findOrFail(Crypt::decryptString($id));
        return view('colaborators.edit-rh-user', compact('colaborator'));
    }

    public function updateRhColaborator(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'user_id' => 'required|exists:users,id', // verifica se existe na tabela users no campo id
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d'
        ]);

        $user = User::findOrFail($request->user_id);

        $user->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date
        ]);

        return redirect()->route('colaborators.rh-users');
    }

    public function deleteRhColaborator($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $colaborator = User::findOrFail(Crypt::decryptString($id));
        return view('colaborators.delete-colaborator-confirm', compact('colaborator')); // view de confirmação
    }

    public function deleteRhColaboratorConfirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $colaborator = User::findOrFail(Crypt::decryptString($id));
        $colaborator->delete();
        return redirect()->route('colaborators.rh-users');
    }
}
