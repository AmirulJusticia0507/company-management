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
            'name' => 'Acme Corp',
            'email' => 'acme@example.com',
            'logo' => '',
            'website' => 'https://acme.com',
        ]);

        Employee::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@acme.com',
            'phone' => '0812345678',
            'company_id' => $company->id,
        ]);

        Employee::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@acme.com',
            'phone' => '08987654321',
            'company_id' => $company->id,
        ]);
    }
}
