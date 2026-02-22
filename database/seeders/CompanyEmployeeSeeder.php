<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Employee;

class CompanyEmployeeSeeder extends Seeder
{
    public function run()
    {
        $company = Company::create([
            'name' => 'CR7 Corp',
            'email' => 'cr7siuuu@gmail.com',
            'logo' => '',
            'website' => 'https://cr7.com',
        ]);

        Employee::create([
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'cr7official@gmail.com',
            'phone' => '0812345678',
            'company_id' => $company->id,
        ]);

        Employee::create([
            'first_name' => 'Lionel Andres',
            'last_name' => 'Messi',
            'email' => 'leomessi10@gmail.com.com',
            'phone' => '08987654321',
            'company_id' => $company->id,
        ]);
    }
}
