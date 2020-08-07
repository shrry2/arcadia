<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Office;
use App\User;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 権限チェックはルーティングで行う
        // $this->middleware('auth');
    }

    public function index() {
        $offices = Office::All();
        $roles = Role::All();

        return view('user.new', ['offices' => $offices, 'roles' => $roles]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $roleIds = Role::All()->pluck('id')->toArray();
        $officeIds = Office::All()->pluck('id')->toArray();

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'office.*' => ['nullable', Rule::in($officeIds)],
            'role.*' => ['nullable', Rule::in($roleIds)],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // 所属の登録
        if (isset($data['office']) && is_array($data['office'])) {
            $newOfficeIds = array_map('intval', $data['office']);
            $user->offices()->sync($newOfficeIds);
        }

        // 権限グループの登録
        if (isset($data['role']) && is_array($data['role'])) {
            $newRoles = array_map('intval', $data['role']);
            $user->syncRoles($newRoles);
        }

        return $user;
    }

    /**
     * Handle a registration request for the application.
     * 登録後勝手にアカウントが切り替わらないよう改変済み
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectTo);
    }
}
