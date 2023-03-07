<?php

namespace App\Providers;

use App\Models\EmailVerificationTokens;
use App\Observers\User\EmailVerificationTokensObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data = [], $message = '', $status_code = 200) {
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => $message
            ], $status_code);
        });

        Response::macro('error', function ($data = [], $message = '', $status_code = 500) {
            return response()->json([
                'status' => false,
                "errors" => $data,
                'message' => $message,
            ], $status_code);
        });

        //EmailVerificationTokens::observe(EmailVerificationTokensObserver::class);

        if (App::environment('production'))
        {
            Schema::defaultStringLength(191);
        }
    }
}
