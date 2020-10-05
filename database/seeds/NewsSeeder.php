<?php

use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news_posts')->insert([
            'title' => 'Test!',
            'message' => 'Dit is het eerste bericht.',
            'user_id' => '1',
            'news_category_id' => '4',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
