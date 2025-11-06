<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function profile($id)
    {
        $admin = User::findOrFail($id);
        return view('admin.profile', [
            'admin' => $admin,
        ]);
    }

    public function updateProfile(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:30|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'currentPassword' => 'required|string',
        ];

        if ($request->filled('password')) {
            $rules['password'] = [
                'string',
                'min:8',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value !== $request->confirm_password) {
                        $fail('Password dan konfirmasi password tidak cocok.');
                    }
                },
            ];
        }

        $request->validate($rules);

        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['currentPassword' => 'Password saat ini salah.']);
        }

        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/user-img', $filename);

            // Hapus foto lama jika bukan default
            if ($user->foto && $user->foto != 'default-user.jpg') {
                \Storage::delete('public/user-img/' . $user->foto);
            }

            $user->foto = $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
    }
}
