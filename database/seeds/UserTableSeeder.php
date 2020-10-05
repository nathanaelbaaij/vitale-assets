<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Avatar;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( App::environment( 'production' ) ) {
            $this->create_user('Daan', 'daan.de.waard@hz.nl', 'change_me', "HZ", ['administrator']);
        } else {
            $this->create_user('admin', 'admin@va.test', 'admin123', "Vitale Assets Test", ['administrator']);
            $this->create_user('editor', 'editor@va.test', 'editor123', "Vitale Assets Test", ['editor']);
            $this->create_user('member', 'member@va.test', 'member123', "Vitale Assets Test", ['member']);
        }
    }

    /**
     * Helper function that creates a single user and an avatar
     */
    private function create_user($username, $email, $password, $company, $roles)
    {
        $new_user = new User();
        $new_user->name = $username;
        $new_user->email = $email;
        $new_user->password = bcrypt($password);
        $new_user->phone = "";
        $new_user->company = $company;
        $new_user->adres = "";
        $new_user->house_number = "";
        $new_user->postal = "";
        $new_user->city = "";
        $new_user->save();

        $new_user->assignRole($roles);

        $avatar = new Avatar;
        $avatar->image_url = "default.png";
        $avatar->active = true;
        $avatar->user_id = $new_user->id;
        $avatar->save();
    }
}
