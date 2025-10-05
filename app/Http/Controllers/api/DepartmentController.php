<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $departments = Department::orderBy('name', 'asc')->get();
        return view('department.departments', compact('departments'));
    }
    public function newDepartment()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('department.add-department');
    }
    public function createDepartment(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $request->validate([
            'name' => 'required|string|max:50|unique:departments' // unico na tabela de departments
        ]);

        Department::create([
            'name' => $request->name
        ]);

        return redirect()->route('departments');
    }

    public function editDepartment($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments');
        }

        $department = Department::findOrFail(Crypt::decryptString($id));

        return view('department.edit-department', compact('department'));
    }

    public function updateDepartment(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $id = $request->id; // recebe o id hidden no formulário

        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:50|unique:departments, name, ' . $id // unico na tabela de departments
        ]);

        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments');
        }

        $department = Department::findOrFail($id);

        $department->update([
            'name' => $request->name
        ]);

        return redirect()->route('departments');
    }

    public function deleteDepartment($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        if ($this->isDepartmentBlocked(Crypt::decryptString($id))) {
            return redirect()->route('departments');
        }

        $department = Department::findOrFail($id);

        //mostrar a view de confirmacao
        return view('department.delete-department-confirm', compact('department'));
    }

    public function deleteDepartmentConfirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        if ($this->isDepartmentBlocked(Crypt::decryptString($id))) {
            return redirect()->route('departments');
        }

        $department = Department::findOrFail(Crypt::decryptString($id));
        $department->delete();

        return redirect()->route('departments');
    }

    private function isDepartmentBlocked($id)
    {
        return in_array(intval($id), [1, 2]);
    }
}
