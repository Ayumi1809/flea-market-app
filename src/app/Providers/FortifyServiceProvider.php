<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 会員登録処理
    Fortify::createUsersUsing(CreateNewUser::class);

    // プロフィール更新
    Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);

    // パスワード変更
    Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

    // パスワードリセット
    Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    // ログイン画面
    Fortify::loginView(function () {
        return view('auth.login');
    });

    // 会員登録画面
    Fortify::registerView(function () {
        return view('auth.register');
    });

    // ログイン試行制限
    RateLimiter::for('login', function (Request $request) {
        $throttleKey = Str::transliterate(
            Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
        );

        return Limit::perMinute(5)->by($throttleKey);
    });

    // 二段階認証試行制限
    RateLimiter::for('two-factor', function (Request $request) {
        return Limit::perMinute(5)
            ->by($request->session()->get('login.id'));
    });
}
}
