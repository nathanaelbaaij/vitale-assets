<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(LoadLevelSeeder::class);
        $this->call(BreachLocationSeeder::class);
        $this->call(NewsCategorySeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(CategorySeeder::class);
        if ( !App::environment( 'production' ) ) {
            $this->call(AssetSeeder::class);
        }
    }
}
