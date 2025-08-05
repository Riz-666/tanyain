<?php

namespace App\Http\Controllers;

use App\Models\Repositori;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(String $id){
        $user = User::with('artikel', 'repositori' ,'viewArtikel')->findOrfail($id);
        return view('profile', [
            'user' => $user
        ]);
    }

    public function edit_profile(String $id){
        $user = User::findOrFail($id);
        return view('profile_edit',[
            'user' => $user
        ]);
    }

    public function update_profile(Request $request, String $id){
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'instagram' => 'nullable',
            'linkedin' => 'nullable',
            'github' => 'nullable',
            'bio' => 'nullable|max:255',
            'foto' => 'nullable|mimes:png,jpg,jpeg,gif|max:2048'
        ],[
            'foto.image' => 'Format Gambar Tidak Sesuai',
            'foto.max' => 'Ukuran File Terlalu Besar, Tidak Bisal Lebih dari 2MB'
        ]);

        $user->instagram = $request->instagram;
        $user->linkedin = $request->linkedin;
        $user->github = $request->github;
        $user->bio = $request->bio;
        if($request->hasFile('foto')){
            $dir = public_path('storage/user-img/');
            $file = $request->file('foto');
            $filename = time().$file->getClientOriginalName();
            $file->move($dir,$filename);
            if(!is_null($user->foto)){
                $oldImagePath = public_path('storage/user-img/'.$user->foto);
                if(file_exists($oldImagePath)){
                    unlink($oldImagePath);
                }
            }
            $user->foto = $filename;
        }
        $result = $user->save($validatedData);
        return redirect()->route('profile.edit',$user->id)->with('success','Profile Berhasil Di Perbarui');
    }
}
