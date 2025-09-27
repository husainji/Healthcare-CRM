<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run()
    {
        // Roles to create
        $roles = [
            'Admin',
            'CRM Agent',
            'Doctor',
            'Patient',
            'Lab Manager',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Seed one user per role
        $this->createUser('admin@example.com', 'Admin User', 'password', 'Admin');
        $this->createUser('crm@example.com', 'CRM Agent', 'password', 'CRM Agent');
        $this->createUser('doctor@example.com', 'Doctor User', 'password', 'Doctor');
        $this->createUser('patient@example.com', 'Patient User', 'password', 'Patient');
        $this->createUser('lab@example.com', 'Lab Manager', 'password', 'Lab Manager');

        // Optional: you can create a multi-role user
        $multi = User::firstOrCreate(
            ['email' => 'multi@example.com'],
            [
                'name' => 'Multi Role',
                'password' => Hash::make('password'),
            ]
        );
        $multi->syncRoles(['Admin', 'Doctor']);
    }

    protected function createUser($email, $name, $password, $role)
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }
        
        // Create the doctor profile and relate to user that role type is doctor
        if ($user->hasRole('Doctor')){
            Doctor::firstOrCreate(
                ['user_id' => $user->id],
                ['specialization' => 'General', 'phone' => '1234567890', 'email' => $user->email]
            );
        }
    }
}
