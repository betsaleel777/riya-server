<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserPostRequest;
use App\Http\Requests\User\UserPutRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $users = User::get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPostRequest $request): JsonResponse
    {
        $request->validated();
        $user = User::make($request->all());
        $user->save();
        $user->addMediaFromRequest('image')->toMediaCollection('photo');
        return response()->json("L'utilisateur $user->name a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResource
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPutRequest $request, User $user)
    {
        $request->validated();
        if ($request->filled('oldPassword')) {
            if (Hash::check($request->password, $user->password)) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->save();
                if ($request->hasFile('image')) {
                    $user->addMediaFromRequest('image')->toMediaCollection('photo');
                }
                return response()->json('Utilisateur modifié avec succès.');
            } else {
                return response()->json("Ancien mot de passe ou email érroné.", 400);
            }
        } else {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            if ($request->hasFile('image')) {
                $user->addMediaFromRequest('image')->toMediaCollection('photo');
            }
            return response()->json('Utilisateur modifié avec succès.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json("L'utilisateur a été supprimé avec succès.");
    }
}
