<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\Types;
use App\Models\Auditor;
use App\Models\Company;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use App\Traits\HashId;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:auditors,email'],
            'phone' => ['required', 'string', 'max:15'],
            'company_name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            $role = Role::where('name', 'admin')->first();
            DB::beginTransaction();
            $company = Company::create([
                'name' => $data['company_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'company_code' => 'EA-' . rand(100, 999) . rand(1000, 9999) . '-A'
            ]);

            $auditor = Auditor::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $role->id,
                'company_id' => $company->id,
                'is_verified' => 1
            ]);

            $company->update(['administrator_id' => $auditor->id]);

            $auditor->profile()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
            ]);

            DB::commit();
            return $auditor;
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
