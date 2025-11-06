<?php

namespace App\Exports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TagsTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua tag terbaru dari DB
        return Tag::select('nama_tag')->get();
    }

    public function headings(): array
    {
        // Header di row pertama
        return ['nama_tag'];
    }
}
