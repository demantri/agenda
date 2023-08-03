<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function doLogin(Request $request)
    {
        $rules = [
            'username'  => 'required|string',
            'password'  => 'required|string',
        ];

        $messages = [
            'required' => ':attribute wajib diisi',
        ];

        $name = [
            'username'  => 'Username',
            'password'  => 'Password',
        ];

        $validate = \Validator::make($request->all(), $rules, $messages);

        $validate->setAttributeNames($name);

        if ($validate->fails())
            return response()->json($validate->messages(), 422);

        $data = [
            'username'       => $request->input('username'),
            'password'  => $request->input('password'),
        ];

        $user = DB::table('users')->where('username', $data['username'])->first();
        
        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->intended('/agenda');
        } else {
            Session::flash('error', 'Username atau Password tidak valid');
            return redirect('/login'); //
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('agenda');
    }
}
