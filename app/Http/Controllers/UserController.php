<?php

namespace App\Http\Controllers;

use Illuminate\Cache\RedisTagSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index()
    {
        $data = DB::select("select * from users");
        return view('user.index', compact('data'));
    }

    public function store(Request $request)
    {
        // validasi
        $request->validate([
            'role_name' => $request->role_name == 'superadmin' ? 'unique:users,role_name' : 'required',
            'username'  => 'required|string|unique:users,username',
            'email'     => 'required|string|unique:users,email',
            'password'  => 'required|string',
        ]);

        // insert
        $data = [
            'role_name' => $request->role_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        
        DB::table('users')->insert($data);

        session()->flash('success', 'Berhasil menyimpan data');

        return redirect()->to('user');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        
        $data = [
            'role_name' => $request->role_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        DB::table('users')
        ->where('id', $id)    
        ->update($data);

        session()->flash('success', 'Berhasil mengubah data');

        return redirect()->to('user');
    }
    
    public function delete($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->delete();

        session()->flash('success', 'Berhasil menghapus data');

        return redirect()->to('user');
    }
}
