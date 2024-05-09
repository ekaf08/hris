<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponApiFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function fetch(Request $request)
    {
        $id     = $request->input('id');
        $name   = $request->input('name');
        $limit  = $request->input('limit', 10);

        $teamQuery = Team::query();

        /** Mengambil satu data team */
        if ($id) {
            $team = $teamQuery->find($id);

            if ($team) {
                return ResponApiFormatter::success($team, 'Team Found');
            }

            return ResponApiFormatter::error('Team not found.', 404);
        }

        /** Mengambil semua data team */
        $teams = $teamQuery->where('company_id', $request->company_id);

        /** mencari berdasarkan nama */
        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }

        /** Respon sukses */
        return ResponApiFormatter::success(
            $teams->paginate($limit),
            'Teams found.'
        );
    }

    public function create(CreateTeamRequest $request)
    {
        try {
            /** cek jika ada lampiran icon */
            if ($request->hasFile('icon')) {
                /** Mengambil id team terakhir */
                $last_id = Team::latest('id')->first()->id;
                $last_id = $last_id + 1;

                /** mendapatkan nama file yang di unggah */
                $fileName = $request->file('icon')->getClientOriginalName();

                /** Membuat nama custom */
                $customName = 'team_' . $last_id . '_' . $fileName;

                /** Menyimpan file ke public/storage */
                $path = $request->file('icon')->storeAs('public/icon-team', $customName);
            }

            /** Proses simpan ke tabel Team */
            $team = Team::create([
                'name' => htmlspecialchars($request->name),
                'icon' => $path,
            ]);

            /** Jika gagal mengisi tabel team */
            if (!$team) {
                throw new Exception('Team not created');
            }

            /** Respon sukses */
            return ResponApiFormatter::success($team, 'Team Created');
        } catch (Exception $e) {
            /** Respon error */
            return ResponApiFormatter::error($e->getMessage(), 500);
        }
    }
}
