<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $user = User::firstWhere('email', $request->email);
            $user->save();
            $request->session()->regenerate();
            return response()->json(['message' => 'Bienvenue Dans Riya immobilier !!']);
        } else {
            throw new AuthenticationException('Adresse ou mot de passe incorrecte');
        }
    }

    /**
     * Undocumented function
     *
     * @param  Request  $request
     * @return void
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function user(Request $request): JsonResource
    {
        $user = User::with('photo:id,model_id,model_type,disk,file_name')->find($request->user()->id);
        return UserResource::make($user);
    }
}
