<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'identity';
    }

    /**
     * 社員コードとメールアドレス両方でログインできるようにoverride
     */
    protected function attemptLogin(Request $request)
    {
        $input = $request->all();

        if (filter_var($input['identity'], FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $input['identity'], 'password' => $input['password']];
        } else {
            $credentials = ['username' => $input['identity'], 'password' => $input['password']];
        }

        return $this->guard()->attempt($credentials, $request->filled('remember'));
    }
}
