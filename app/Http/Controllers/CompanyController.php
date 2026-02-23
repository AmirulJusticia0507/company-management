<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::latest()->paginate(10);

        return Inertia::render('Companies/Index', [
            'companies' => $companies
        ]);
    }

    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')
                ->store('companies', 'public');
        }

        Company::create($data);

        return redirect()->back()->with('success', 'Company created successfully');
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {

            // hapus logo lama jika ada
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $data['logo'] = $request->file('logo')
                ->store('companies', 'public');
        }

        $company->update($data);

        return redirect()->back()->with('success', 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()->back()->with('success', 'Company deleted successfully');
    }
}
