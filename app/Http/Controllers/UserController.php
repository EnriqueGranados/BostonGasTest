<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        $users = User::all(); // Obtiene todos los usuarios
        return view('create', compact('users'));  
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        // Validación de los campos
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255', // Validación para last_name
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'address' => 'nullable|string|max:255', // Validación para address
            'phone_number' => 'nullable|string|max:20', // Validación para phone_number
            'birth_date' => 'nullable|date', // Validación para birth_date
            'gender' => 'nullable|string|max:10', // Validación para gender
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Asegúrate de hashear la contraseña
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
        ]);

        return redirect()->route('users.create')->with('success', 'Usuario creado con éxito.');
    }

    // Función para eliminar el usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.create')->with('success', 'Usuario eliminado con éxito.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // Obtiene el usuario por ID
        return view('editUser', compact('user')); // Retorna la vista editUser con el usuario
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all()); // Actualiza el usuario con los datos proporcionados

        return redirect()->route('users.create')->with('success', 'Usuario actualizado con éxito.'); // Redirige a la vista create
    }
}
