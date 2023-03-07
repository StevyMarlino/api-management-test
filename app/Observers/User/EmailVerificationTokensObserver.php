<?php

namespace App\Observers\User;

use App\Mail\VerifyAccount;
use App\Models\EmailVerificationTokens;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationTokensObserver
{
    /**
     * Handle the EmailVerificationTokens "created" event.
     *
     * @param EmailVerificationTokens $emailVerificationTokens
     * @return void
     */
    public function created(EmailVerificationTokens $emailVerificationTokens)
    {
        $user = User::find($emailVerificationTokens->user_id);

        Mail::to($user->email)->send(new VerifyAccount($user));

        $role = config('roles.models.role')::where('name', '=', 'Unverified')->first();  //choose the default role upon user creation.
        $user->attachRole($role);
    }

    /**
     * Handle the EmailVerificationTokens "updated" event.
     *
     * @param EmailVerificationTokens $emailVerificationTokens
     * @return void
     */
    public function updated(EmailVerificationTokens $emailVerificationTokens)
    {
        //
    }

    /**
     * Handle the EmailVerificationTokens "deleted" event.
     *
     * @param EmailVerificationTokens $emailVerificationTokens
     * @return void
     */
    public function deleted(EmailVerificationTokens $emailVerificationTokens)
    {
        //
    }

    /**
     * Handle the EmailVerificationTokens "restored" event.
     *
     * @param EmailVerificationTokens $emailVerificationTokens
     * @return void
     */
    public function restored(EmailVerificationTokens $emailVerificationTokens)
    {
        //
    }

    /**
     * Handle the EmailVerificationTokens "force deleted" event.
     *
     * @param EmailVerificationTokens $emailVerificationTokens
     * @return void
     */
    public function forceDeleted(EmailVerificationTokens $emailVerificationTokens)
    {
        //
    }
}
