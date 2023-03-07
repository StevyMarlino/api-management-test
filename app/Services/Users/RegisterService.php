<?php


namespace App\Services\Users;


use App\Models\EmailVerificationTokens;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterService
{
    /*
     *
     */
    public function save($data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'last_name' => $data->last_name,
                'first_name' => $data->first_name,
                'password' => Hash::make($data->password),
                'email' => $data->email,
                'city' => $data->city,
                'phone' => $data->phone,
            ]);

            $this->createVerificationToken($user);
            return $user;
        });
    }

    public static function createVerificationToken($user)
    {
        try {
            DB::beginTransaction();
            EmailVerificationTokens::create([
                'user_id' => $user->id,
                'token' => Str::random(12),
                'token_expiration' => Carbon::now()->addDay()
            ]);

            $token = $user->createToken('api-management')->plainTextToken;

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error([], $e->getMessage(), 422);
        }

        DB::commit();
        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function verifyToken($token)
    {
        try {
            $verification = EmailVerificationTokens::where('token', $token)->first();
            if ($verification) {
                $user = $verification->user;

                if ($user->email_verified_at) {
                    return response()->error([], 'Account Already verified', 400);
                }

                if (!$user->email_verified_at && Carbon::now()->lt($verification->token_expiration)) {
                    $user->email_verified_at = Carbon::now();
                    $user->update([]);

                    $role = config('roles.models.role')::where('name', '=', 'User')->first();  //choose the default role upon user verify.
                    $user->attachRole($role);

                    return response()->success(
                        ['user' => $user, 'token' => $user->createToken("api-management")->plainTextToken]
                        , 'User verified successfully');
                } else {
                    $verification->delete();
                    $this->createVerificationToken($user);

                    return response()->success([], 'Check your email to verify  your account');
                }
            }
        } catch (\Exception $e) {
            return response()->error([], $e->getMessage(), 400);
        }
    }
}
