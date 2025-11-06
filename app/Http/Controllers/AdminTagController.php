<?php

namespace App\Http\Controllers;

use App\Exports\TagsTemplateExport;
use App\Imports\TagsImport;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AdminTagController extends Controller
{
    public function index()
    {
        $tag = Tag::orderBy('created_at', 'desc')->get();
        return view('admin.tag.tag', ['tag' => $tag]);
    }
    public function create()
    {
        return view('admin.tag.create');
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nama_tag' => 'required|string|max:50',
        ]);

        Tag::create([
            'nama_tag' => $request->nama_tag,
            'slug' => Str::slug($request->nama_tag),
        ]);

        return redirect()->route('admin.tag')->with('success', 'Berhasil Menambahkan Tag');
    }

    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);

        // Hapus relasi pivot artikel_tag
        $tag->artikel()->detach();

        // Hapus tag
        $tag->delete();

        return redirect()->route('admin.tag')->with('success', 'Tag berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new TagsTemplateExport(), 'template_tag.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Simpan file baru ke storage/public/admin/templateExcel (ganti yang lama)
        $request->file('file')->storeAs(
            'admin/templateExcel', // folder di storage/public
            'template_tag.xlsx', // nama file template
            'public', // disk public
        );

        // Import data dari file yang di-upload
        Excel::import(new TagsImport(), $request->file('file'));

        return back()->with('success', 'Data tags berhasil diimport dan template diganti!');
    }
}
