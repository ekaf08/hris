<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Helpers\RespondApiPaginate;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

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

    public function create(CreateCompanyRequest $request)
    {
        try {
            /** cek jika ada foto maka Upload foto ke folder publik */
            if ($request->hasFile('logo')) {
                /** Mengambil ID terakhir*/
                $last_id = Company::latest('id')->first()->id;
                $last_id = $last_id + 1;

                /** Mendapatkan nama file yang diunggah*/
                $fileName = $request->file('logo')->getClientOriginalName();

                /** Membuat nama kustom */
                $customName = 'company_' . $fileName . '_'  . $last_id;

                /** Menyimpan file ke public/storage dengan nama kustom */
                $path = $request->file('logo')->storeAs('public/logos', $customName);
            }

            /** Proses simpan ke tabel company */
            $company = Company::create([
                'name' => $request->name,
                'logo' => $path,
            ]);

            /** insert ke dalam tabel company user dari relasi many to many (company dan user) */
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            if (!$company) {
                throw new Exception('Company not created.');
            }

            /** Load User */
            $company->load('users');

            /** Return Response Success*/
            return ResponApiFormatter::success($company, 'Company Created');
        } catch (Exception $exception) {
            return ResponApiFormatter::error($exception->getMessage(), 500);
        }
    }

    public function update(UpdateCompanyRequest $request, $id)
    {
        try {
            /** Mencari company berdasarkan id */
            $company = Company::find($id);

            /** Respons jika company tidak di temukan */
            if (!$company) {
                throw new Exception('Company tidak di temukan');
            }

            /** cek jika ada foto maka Upload foto ke folder publik */
            if ($request->hasFile('logo')) {
                /** Mengambil ID terakhir*/
                $last_id = Company::latest('id')->first()->id;
                $last_id = $last_id + 1;

                /** Mendapatkan nama file yang diunggah*/
                $fileName = $request->file('logo')->getClientOriginalName();

                /** Membuat nama kustom */
                $customName = 'company_' . $fileName . '_'  . $last_id;

                /** Menyimpan file ke public/storage dengan nama kustom */
                $path = $request->file('logo')->storeAs('public/logos', $customName);
            }

            /** Update company */
            $company->update([
                'name'  => $request->name,
                'logo'  => $path
            ]);

            /** Respons update */
            return ResponApiFormatter::success($company, 'Company berhasil di perbarui');
        } catch (Exception $e) {
            /** Respon error */
            return ResponApiFormatter::error($e->getMessage(), 500);
        }
    }
}
