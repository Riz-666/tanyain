<?php

namespace App\Imports;

use App\Models\Tag;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TagsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $namaTag = $row['nama_tag'];

        if (Tag::where('nama_tag', $namaTag)->exists()) {
            return null; // skip kalau nama_tag sudah ada
        }

        $slug = Str::slug($namaTag);
        $originalSlug = $slug;
        $counter = 1;

        // cek slug unik
        while (Tag::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return new Tag([
            'nama_tag' => $namaTag,
            'slug' => $slug,
        ]);
    }
}
