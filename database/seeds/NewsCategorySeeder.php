<?php

use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news_categories')->insert([
            'name' => 'Gevaar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('news_categories')->insert([
            'name' => 'Waarschuwing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('news_categories')->insert([
            'name' => 'Succes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('news_categories')->insert([
            'name' => 'Informatie',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
