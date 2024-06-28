<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = DB::table('users')->orderBy('id', 'desc')->get();
        return response(compact('users'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' =>'required|string|max:55',
            'email' =>'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create($data);
        $message = 'Utilisateur créé avec succès!';
        return response(compact('message'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response(compact('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' =>'required|string|max:55',
            'email' =>'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (isset($data['password'])) {
            $data['password'] =  bcrypt($data['password']);
        }

        $user->update($data);

        return response([
           'message' => 'Utilisateur modifié avec succès'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response([
            'message' => 'Utilisateur supprimé avec succès'
        ], 200);
    }
}
