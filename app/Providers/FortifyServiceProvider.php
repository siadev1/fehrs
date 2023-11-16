<?php

namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Support\Facades\Mail;
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return response()->json("logout successfull");
            }
        });    

        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                /**
                 * @var User $user
                 */
                $user = $request->user();
                return $request->wantsJson()
                    ? response()->json(['two_factor' => false,
                                        'name'=>$user->name, 
                                        'email' => $user->email,
                                        'role_id'=>$user->role_id,
                                        'role'=>$user->role->name
                                         ])
                    : redirect()->intended(Fortify::redirects('login'));
            }
        });



        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                /**
                 * @var User $user
                 */
                $user = $request->user();
                // Mail::to($request->user())->send(new WelcomeMail($user));
                return $request->wantsJson()
                    ? response()->json(['two_factor' => false,
                                        'name'=>$user->name, 
                                        'email' => $user->email,
                                        'role_id'=>$user->role_id
                                         ])
                    : redirect()->intended(Fortify::redirects('login'));
               
            }
        });
    
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Fortify::authenticateUsing(function (Request $request) {
        //     $user = User::where('email', $request->email)->first();
     
        //     if ($user &&
        //         Hash::check($request->password, $user->password)) {
        //         return response()->json($user);
        //     }
        // });
    }
}
