<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Services\Users\RegisterService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function createUser(RegisterRequest $request)
    {
        try {

            (new RegisterService)->save($request);

            return response()->success([] ,'User Created Successfully, Check Your Mail for verification');

        } catch (\Exception $th) {
            return response()->error([], $th->getMessage(), 400);
        }
    }

    public function login(LoginRequest $request)
    {
        // Verification des crédentiel
        if (!$this->InvalidCredential($request))  return response()->error([], 'Invalid credentials', 400);

        // Verification de la vérificaiton du mail
        if (!$this->emailVerification()) return response()->error([], 'You need to verify your account, check your email', 401);

        // Verification si le compte est activé.
        if(!$this->accountIsActivate()) return response()->error([], 'Your account is Desactivaterd contact the Admin', 401);

        $user = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'user' => $user,
            'token' => $user->createToken("api-management")->plainTextToken
        ], 200);

    }

    private function InvalidCredential($request)
    {
        return Auth::attempt($request->only('email', 'password'));
    }

    private function emailVerification()
    {
        return Auth::user()->email_verified_at;
    }

    private function accountIsActivate()
    {
        return Auth::user()->is_active;
    }

    /**
     * @param $token
     * @return RegisterService
     * @unauthenticated
     */

    public function verifyUser($token)
    {
        return (new RegisterService())->verifyToken($token);
    }

}
