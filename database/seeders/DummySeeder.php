<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DummySeeder extends Seeder
{
    public function run()
    {
        $json = File::get(database_path('data_json/dummy.json'));
        $data = json_decode($json, true);

        foreach ($data['repositori'] as $repo) {
            DB::table('repositori')->insert($repo);
        }

        foreach ($data['artikel'] as $artikel) {
            DB::table('artikel')->insert($artikel);
        }

        foreach ($data['file_repo'] as $file) {
            DB::table('file_repo')->insert($file);
        }
    }
}
