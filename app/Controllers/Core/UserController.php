<?php

namespace App\Controllers\Core;

use App\Models\Role;
use App\Models\User;
use Sakuci\Controller;
use Sakuci\Http\Request;

/** Halaman admin untuk menambah user dan menentukan role-nya. */
class UserController extends Controller
{
    public function index()
    {
        return view('core.admin.users.index', [
            'users' => User::orderBy('username')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|min:3|max:50|alpha_dash|unique:users,username',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        User::create([
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => $data['role'],
        ]);

        return back()->with('success', 'User "' . $data['username'] . '" berhasil ditambahkan.');
    }

    /** Ubah username, role, dan (opsional) password user. */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        $data = $request->validate([
            'username' => 'required|min:3|max:50|alpha_dash',
            'password' => 'nullable|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        // Cek username tidak bentrok dengan user lain
        $dipakai = User::where('username', $data['username'])->where('id', '!=', $user->id)->first();
        if ($dipakai) {
            return back()->with('error', 'Username "' . $data['username'] . '" sudah dipakai user lain.');
        }

        $user->username = $data['username'];
        $user->role     = $data['role'];
        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $user->save();

        return back()->with('success', 'User "' . $user->username . '" berhasil diubah.');
    }

    /** Hapus user. User dengan role admin tidak boleh dihapus. */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'User dengan role admin tidak boleh dihapus.');
        }

        $nama = $user->username;
        $user->delete();

        return back()->with('success', 'User "' . $nama . '" berhasil dihapus.');
    }
}