<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials))
                return response()->json(['message' => 'Invalid Credentials', "code" => 400]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Something went wrong', "code" => 500]);
        }

        return response()->json(['message' => 'Successful', "code" => 200, 'data' => ["token" => $token, "user" => $this->user()]]);
    }

    private function user()
    {
        return response()->json(auth()->user());
    }

}
