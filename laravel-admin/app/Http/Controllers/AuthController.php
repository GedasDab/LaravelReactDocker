<?php

namespace App\Http\Controllers;

use Cookie;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function helloMethod () {
        return 'hello Gedas';
    }

    /**
     * Creating user.
     */
    public function register (RegisterRequest $request) {
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => 1
        ]);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * Creating cookie and jwt.
     */
    public function login(Request $request)
    {
        /**
         * Need to check email and password
         * return true/false
         */
        if (!Auth::attempt($request->only('email', 'password'))) {
            return \response([
                'error' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = Auth::user();
        $jwt = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $jwt, 60 * 24); // 1d.
        return \response([
            'jwt' => $jwt
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        //return $request->user();
        return new UserResource($user->load('role'));
    }

    /**
     * Loging out. We are removing cookie.
     */
    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return \response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = $request->user();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
        //return response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
        //return response($user, Response::HTTP_ACCEPTED);
    }
}
