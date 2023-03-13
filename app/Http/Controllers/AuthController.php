<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginStoreRequest;
use App\Http\Requests\RegisterStoreRequest;
use App\Http\Requests\UpdateUserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function register(RegisterStoreRequest $request)
   {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('secret')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
   }
   public function login(loginStoreRequest $request)
   {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid Email Or Password'
            ]);
        }
        $token = $user->createToken('secret')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
   }
   public function logout()
   {
    auth()->user()->tokens()->delete();
    return response([
        'message' => 'Logout Success'
    ]);
   }

   public function user()
   {
        return response([
            'user' => auth()->user()
        ]);
   }

   public function update(UpdateUserStoreRequest $request)
   {
        $request->validated();

        $image = $this->saveimage($request->image, 'profiles');

        auth()->user()->update([
            'name' => $request->name,
            'image' => $image
        ]);

        return response([
            'message' => 'user updated',
            'user' => auth()->user()
        ]);
   }
}
