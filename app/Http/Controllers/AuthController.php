<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiHelpers;
    /**
     * constructor.
     */
    public function __construct(){
        $this->middleware('auth:sanctum')->only('logout');
    }

    /**
     * Register a new user
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
            'role_id' => 'required|numeric'
        ]);
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role_id' => $fields['role_id']
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    /**
     * Login a user with email and password
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6'
        ]);
        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }
        $token = $user->createToken('auth_token', [($user->role->name)])->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    /**
     * Logout the user
     *
     * @return Response
     */
    public function logout(): Response
    {
        auth()->user()->currentAccessToken()->delete();
        return response(['message' => 'Logged out!']);
    }
}
