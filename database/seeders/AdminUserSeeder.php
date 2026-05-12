<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
	public function run()
	{
		User::updateOrCreate(
			['email' => 'admin@admin.com'],
			[
				'name' => 'Admin',
				'password' => 'password',
				'role' => 'admin',
			]
		);

		User::updateOrCreate(
			['email' => 'nicod162005@gmail.com'],
			[
				'name' => 'Nicolas',
				'password' => 'Nicomat@1617',
				'role' => 'admin',
			]
		);
	}
}
