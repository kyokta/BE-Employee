<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisiController extends Controller
{
    public function getDivision(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string'
        ]);

        $query = Division::query();
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        $divisi = $query->paginate(10);

        $data = $divisi->items();
        $data = array_map(function ($divisi) {
            return [
                'id' => $divisi->id,
                'name' => $divisi->name,
            ];
        }, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data divisi berhasil diambil',
            'data' => [
                'divisions' => $data,
            ],
            'pagination' => [
                'total' => $divisi->total(),
                'current_page' => $divisi->currentPage(),
                'per_page' => $divisi->perPage(),
                'last_page' => $divisi->lastPage(),
                'next_page_url' => $divisi->nextPageUrl(),
                'prev_page_url' => $divisi->previousPageUrl(),
            ],
        ]);
    }
}