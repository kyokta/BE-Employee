<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function getEmployee(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'division_id' => 'nullable|integer|exists:divisions,id',
        ]);

        $query = Employee::query();

        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('division_id') && !empty($request->division_id)) {
            $query->where('divisi', $request->division_id);
        }

        $employees = $query->paginate(10);

        $data = $employees->items();
        $data = array_map(function ($employees) {
            $divisi = Division::find($employees->divisi);

            return [
                'id' => $employees->id,
                'image' => $employees->image ? url($employees->image) : null,
                'name' => $employees->name,
                'phone' => $employees->phone,
                'division' => [
                    'id' => $divisi ? $divisi->id : null,
                    'name' => $divisi ? $divisi->name : null,
                ],
                'position' => $employees->position,
            ];
        }, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pegawai berhasil diambil',
            'data' => [
                'employees' => $data,
            ],
            'pagination' => [
                'total' => $employees->total(),
                'current_page' => $employees->currentPage(),
                'per_page' => $employees->perPage(),
                'last_page' => $employees->lastPage(),
                'next_page_url' => $employees->nextPageUrl(),
                'prev_page_url' => $employees->previousPageUrl(),
            ],
        ]);
    }

    public function storeEmployee(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'division' => 'required|integer|exists:divisions,id',
                'position' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images', $imageName);
            }

            Employee::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'divisi' => $request->division,
                'position' => $request->position,
                'image' => $imagePath ? Storage::url($imagePath) : null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data pegawai.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateEmployee(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'division' => 'nullable|integer|exists:divisions,id',
                'position' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $employee = Employee::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($employee->image) {
                    Storage::delete(str_replace('/storage/', 'public/', $employee->image));
                }

                $image = $request->file('image');
                $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images', $imageName);

                $employee->image = Storage::url($imagePath);
            }

            $employee->name = $request->input('name', $employee->name);
            $employee->phone = $request->input('phone', $employee->phone);
            $employee->divisi = $request->input('division', $employee->divisi);
            $employee->position = $request->input('position', $employee->position);
            $employee->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil diperbarui',
                'data' => [
                    'employee' => [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'phone' => $employee->phone,
                        'divisi' => $employee->divisi,
                        'position' => $employee->position,
                        'image' => $employee->image ? url($employee->image) : null,
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data pegawai.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteEmployee($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            if ($employee->image) {
                Storage::delete(str_replace('/storage/', 'public/', $employee->image));
            }

            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data pegawai.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}