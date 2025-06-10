<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Prestasi;
use App\Models\PrestasiNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class VerifikasiPrestasiController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Verifikasi Prestasi',
            'list' => ['Home', 'Verifikasi Prestasi'],
        ];

        $page = (object) [
            'title' => 'Daftar prestasi yang diajukan',
        ];

        $activeMenu = 'prestasi';

        if ($request->ajax()) {
            return view('prestasi.partial-index', compact('breadcrumb', 'page', 'activeMenu'));
        }

        return view('admin.prestasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function getData()
    {
        $prestasis = Prestasi::with(['dosens', 'mahasiswas'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $prestasis,
        ]);
    }
    public function approve(Request $request, $id)
    {
        if (!Auth::guard('dosen')->check() || Auth::guard('dosen')->user()->role !== 'admin') {
            Log::warning('Unauthorized access attempt', [
                'user_id' => Auth::guard('dosen')->check() ? Auth::guard('dosen')->id() : null,
                'role' => Auth::guard('dosen')->check() ? Auth::guard('dosen')->user()->role : 'not authenticated',
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Tidak diizinkan menyetujui prestasi.',
            ], 403);
        }

        try {
            $prestasi = Prestasi::findOrFail($id);
            $prestasi->update(['status' => 'disetujui']);

            $note = PrestasiNote::create([
                'prestasi_id' => $prestasi->id,
                'dosen_id' => Auth::guard('dosen')->id(),
                'status' => 'disetujui',
                'note' => null,
            ]);

            $mahasiswaList = DB::table('prestasi_mahasiswa')->where('prestasi_id', $prestasi->id)->get();

            foreach ($mahasiswaList as $mhs) {
                DB::table('mahasiswa_prestasi_notes')->insert([
                    'mahasiswa_id' => $mhs->mahasiswa_id,
                    'prestasi_notes_id' => $note->id,
                    'is_accepted' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info('Prestasi approved', ['prestasi_id' => $prestasi->id]);

            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil disetujui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error approving prestasi', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        if (!Auth::guard('dosen')->check() || Auth::guard('dosen')->user()->role !== 'admin') {
            abort(403, 'Tidak diizinkan menolak prestasi.');
        }

        $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        $prestasi = Prestasi::findOrFail($id);
        $prestasi->update(['status' => 'ditolak']);

        $note = PrestasiNote::create([
            'prestasi_id' => $prestasi->id,
            'dosen_id' => Auth::guard('dosen')->id(),
            'status' => 'ditolak',
            'note' => $request->input('note'),
        ]);

        $mahasiswaList = DB::table('prestasi_mahasiswa')->where('prestasi_id', $prestasi->id)->get();

        foreach ($mahasiswaList as $mhs) {
            DB::table('mahasiswa_prestasi_notes')->insert([
                'mahasiswa_id'       => $mhs->mahasiswa_id,
                'prestasi_notes_id'  => $note->id,
                'is_accepted'        => false,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil ditolak.',
        ]);
    }

    public function show($id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Prestasi',
            'list' => ['Home', 'Prestasi', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail Prestasi',
        ];

        $activeMenu = 'prestasi';

        $prestasi = Prestasi::with(['dosens', 'mahasiswas', 'notes'])->findOrFail($id);

        if (!auth()->guard('dosen')->check()) {
            abort(403, 'Tidak diizinkan mengakses data ini.');
        }

        return view('admin.prestasi.detail', compact('breadcrumb', 'page', 'activeMenu', 'prestasi'));
    }

    public function export()
    {
        $prestasis = Prestasi::with(['dosens', 'mahasiswas.programStudi', 'bidangs.lomba'])
            ->where('status', 'disetujui')
            ->get();
        $pdf = Pdf::loadView('admin.prestasi.export', compact('prestasis'))->setPaper('a4', 'landscape');
        return $pdf->download('daftar-prestasi-disetujui.pdf');
    }

}
