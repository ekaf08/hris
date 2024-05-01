<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Helpers\RespondApiPaginate;

class CompanyController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        /** untuk mengambil api dengan filter id, url : hris/api/company?id=$id */
        if ($id) {
            $company = Company::with(['users'])->find($id);

            if ($company) {
                return ResponApiFormatter::success($company, 'Company ditemukan.');
            }
            return ResponApiFormatter::error('Company tidak ditemukan.', 404);
        }

        /** untuk mengambil api semua company dengan mengecek filter name, */
        $companies = Company::with(['users']);

        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ResponApiFormatter::success($companies->paginate($limit), 'Company ditemukan.');
    }
}
