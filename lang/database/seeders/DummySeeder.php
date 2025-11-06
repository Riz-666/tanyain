<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DummySeeder extends Seeder
{
    public function run()
    {
        // Nonaktifin foreign key supaya aman truncate
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \DB::table('file_repo')->truncate();
        \DB::table('artikel')->truncate();
        \DB::table('repositori')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $json = File::get(database_path('data_json/dummy.json'));
        $data = json_decode($json, true);

        foreach ($data['repositori'] as $repo) {
            DB::table('repositori')->insert($repo);
        }

        foreach ($data['artikel'] as $artikel) {
            DB::table('artikel')->insert($artikel);
        }

        foreach ($data['file_repo'] as $file) {
            $file['created_at'] = $file['created_at'] ?? now()->toDateTimeString();
            $file['updated_at'] = $file['updated_at'] ?? now()->toDateTimeString();

            DB::table('file_repo')->insert($file);
        }
    }
}
