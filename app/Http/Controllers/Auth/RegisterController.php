<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use DB;

use App\Models\Users\Subjects;

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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function registerView()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    // protected function validator(array $data)
    // {
    //     // ↓(検証する配列値(名前),検証ルール)
    //     return Validator::make($data, [
    //         'over_name' => 'required|string|max:10',
    //         'under_name' => 'required|string|max:10',
    //         'over_name_kana' => 'required|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u|string|max:30',
    //         'under_name_kana' => 'required|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u|string|max:30',
    //         'mail_address' => 'required|string|email|max:100|unique:users',
    //         'sex' => 'required|integer|max:3',

    //         'old_year' => 'nullable|present|numeric|required_with:month,day',
    //         'old_month' => 'nullable|present|numeric|required_with:year,day',
    //         'old_day' => 'nullable|present|numeric|required_with:year,month',
    //         'role' => 'required|integer|max:4',
    //         'password' => 'required|confirmed',
    //         'password_confirmation' => 'required',
    //     ]);
    //     return $validator;
    // }


    public function registerPost(TestRequest $request)
    {
        // $data = $request->input();
        // $validator = $this->Validator($data);
        // if ($validator->fails()) {
        //     return redirect('/register')
        //         ->withInput()
        //         // ()内でerror送るよ
        //         ->withErrors($validator);
        // }
        DB::beginTransaction();


        // $old_year = $request->old_year;
        // $old_month = $request->old_month;
        // $old_day = $request->old_day;
        // $data = $old_year . '-' . $old_month . '-' . $old_day;
        // $birth_day = date('Y-m-d', strtotime($data));
        // $subjects = $request->subject;


        // $user_get = User::create([
        //     'over_name' => $request->over_name,
        //     'under_name' => $request->under_name,
        //     'over_name_kana' => $request->over_name_kana,
        //     'under_name_kana' => $request->under_name_kana,
        //     'mail_address' => $request->mail_address,
        //     'sex' => $request->sex,
        //     'birth_day' => $birth_day,
        //     'role' => $request->role,
        //     'password' => bcrypt($request->password)
        // ]);

        // $user = User::findOrFail($user_get->id);
        // $user->subjects()->attach($subjects);

        // DB::commit();
        // return view('auth.login.login');



        $old_year = $request->old_year;
        $old_month = $request->old_month;
        $old_day = $request->old_day;
        $data = $old_year . '-' . $old_month . '-' . $old_day;
        $birth_day = date('Y-m-d', strtotime($data));
        $subjects = $request->subject;


        $user_get = User::create([
            'over_name' => $request->over_name,
            'under_name' => $request->under_name,
            'over_name_kana' => $request->over_name_kana,
            'under_name_kana' => $request->under_name_kana,
            'mail_address' => $request->mail_address,
            'sex' => $request->sex,
            'birth_day' => $birth_day,
            'role' => $request->role,
            'password' => bcrypt($request->password)
        ]);

        $user = User::findOrFail($user_get->id);
        $user->subjects()->attach($subjects);

        DB::commit();
        return view('auth.login.login');

        // try {

        //     $old_year = $request->old_year;
        //     $old_month = $request->old_month;
        //     $old_day = $request->old_day;
        //     $data = $old_year . '-' . $old_month . '-' . $old_day;
        //     $birth_day = date('Y-m-d', strtotime($data));
        //     $subjects = $request->subject;


        //     $user_get = User::create([
        //         'over_name' => $request->over_name,
        //         'under_name' => $request->under_name,
        //         'over_name_kana' => $request->over_name_kana,
        //         'under_name_kana' => $request->under_name_kana,
        //         'mail_address' => $request->mail_address,
        //         'sex' => $request->sex,
        //         'birth_day' => $birth_day,
        //         'role' => $request->role,
        //         'password' => bcrypt($request->password)
        //     ]);

        //     $user = User::findOrFail($user_get->id);
        //     $user->subjects()->attach($subjects);

        //     DB::commit();
        //     return view('auth.login.login');
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect()->route('loginView');
        // }
    }
}
