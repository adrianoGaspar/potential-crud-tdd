<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->name = 'Admin Crud';
        $admin->email = 'admin@crud.com';
        $admin->password = bcrypt('admin');
        $admin->save();

        $this->command->info('Usuários cadastrados!');
    }
}
