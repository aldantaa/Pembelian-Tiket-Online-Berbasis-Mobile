<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    /**
     * Menampilkan seluruh data armada.
     */
    public function index()
    {
        $buses = Bus::withCount('schedules')
            ->orderBy('nama_bus')
            ->get();

        return response()->json([
            'message' => 'Data armada berhasil diambil.',
            'data' => $buses,
        ]);
    }

    /**
     * Menambahkan armada baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bus' => ['required', 'string', 'max:100', 'unique:buses,nama_bus'],
            'nomor_polisi' => ['nullable', 'string', 'max:20', 'unique:buses,nomor_polisi'],
            'kapasitas' => ['required', 'integer', 'in:40'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $bus = Bus::create($validated);

        return response()->json([
            'message' => 'Armada berhasil ditambahkan.',
            'data' => $bus,
        ], 201);
    }

    /**
     * Memperbarui data armada.
     */
    public function update(Request $request, int $id)
    {
        $bus = Bus::findOrFail($id);

        $validated = $request->validate([
            'nama_bus' => [
                'required',
                'string',
                'max:100',
                Rule::unique('buses', 'nama_bus')->ignore($bus->id),
            ],
            'nomor_polisi' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('buses', 'nomor_polisi')->ignore($bus->id),
            ],
            'kapasitas' => ['required', 'integer', 'in:40'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $bus->update($validated);

        return response()->json([
            'message' => 'Data armada berhasil diperbarui.',
            'data' => $bus->fresh(),
        ]);
    }

    /**
     * Menghapus armada yang belum digunakan jadwal.
     */
    public function destroy(int $id)
    {
        $bus = Bus::findOrFail($id);

        if ($bus->schedules()->exists()) {
            return response()->json([
                'message' => 'Armada tidak dapat dihapus karena masih digunakan oleh jadwal.',
            ], 409);
        }

        $bus->delete();

        return response()->json([
            'message' => 'Armada berhasil dihapus.',
        ]);
    }
}
