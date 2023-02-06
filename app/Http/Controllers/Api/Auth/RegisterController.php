<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformers\Users\UserTransformer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function validateThis($request, $rules = array())
    {
        return Validator::make($request->all(), $rules);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'password_confirmation' => 'required|same:password',
        ];

        $validator = $this->validateThis($request, $rules);
        if ($validator->fails())
        return response()->json((object)['Params not complete']);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'uuid' => rand(0, 1000000000),
            'password' => Hash::make($request->password),
        ];

        $dataLogin = ['email' => $request->email, 'password' => $request->password];

        $user = new User();
        $user->uuid = rand(0, 1000000000);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        DB::beginTransaction();
        try {
            // $user->sendApiEmailVerificationNotification();
            $insertRegis = DB::table('users')->insert($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json((object)['msg' => $e->getMessage()]);
        }

        Auth::attempt($dataLogin);
        $success = Auth::user();
        $success['token'] = Auth::user()->createToken(Auth::guard()->user()->email)->accessToken;
        $success['email']  = Auth::user()->email;
        $success['name']  = Auth::user()->name;

        return response()->json($success);
    }
}
