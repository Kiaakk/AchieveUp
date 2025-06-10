<?php

namespace App\Http\Controllers;

use App\Models\PengajuanLombaAdminNote;
use App\Models\PengajuanPrestasiAdminNote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotifikasiAdmin extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Semua Notifikasi',
            'list' => ['Notifikasi']
        ];

        $page = (object)[
            'title' => 'Semua Notifikasi'
        ];

        $activeMenu = '';

        $pesan = $this->getAllNotifikasi();

        return view('admin.notifikasi.index', compact('breadcrumb', 'page', 'activeMenu', 'pesan'));
    }

    public function show($type, $id)
    {
        $adminId = auth('dosen')->id();

        if ($type === 'pengajuan_lomba') {
            $note = PengajuanLombaAdminNote::with(['pengajuanLombaMahasiswa.lomba'])
                ->where('id', $id)
                ->where('dosen_id', $adminId)
                ->first();

            if (!$note) {
                abort(404, 'Notifikasi pengajuan lomba tidak ditemukan.');
            }

            $lomba = optional(optional($note->pengajuanLombaMahasiswa)->lomba);
            $pengajuan = $note->pengajuanLombaMahasiswa;

            // dd($pengajuan->id);

            return view('admin.notifikasi.detail', [
                'breadcrumb' => (object)['title' => 'Detail Notifikasi', 'list' => ['Notifikasi', 'Pengajuan Lomba']],
                'page' => (object)['title' => 'Detail Notifikasi Pengajuan Lomba'],
                'activeMenu' => '',
                'pengajuanLomba' => $note,
                'lomba' => $lomba,
                'pengajuan' => $pengajuan,
            ]);
        }

        if ($type === 'pengajuan_prestasi') {
            $note = PengajuanPrestasiAdminNote::with(['prestasi'])
                ->where('id', $id)
                ->where('dosen_id', $adminId)
                ->first();

            if (!$note) {
                abort(404, 'Notifikasi pengajuan prestasi tidak ditemukan.');
            }

            $prestasi = $note->prestasi;
            // dd($prestasi->id);
            // Komen Sembarang

            return view('admin.notifikasi.detail', [
                'breadcrumb' => (object)['title' => 'Detail Notifikasi', 'list' => ['Notifikasi', 'Pengajuan Prestasi']],
                'page' => (object)['title' => 'Detail Notifikasi Pengajuan Prestasi'],
                'activeMenu' => '',
                'pengajuanPrestasi' => $note,
                'prestasi' => $prestasi,
            ]);
        }

        abort(404, 'Tipe notifikasi tidak valid.');
    }

    public function getAllNotifikasi()
    {
        $adminId = auth('dosen')->id();

        // Notifikasi pengajuan lomba untuk admin
        $pengajuanLomba = PengajuanLombaAdminNote::with(['pengajuanLombaMahasiswa.lomba'])
            ->where('dosen_id', $adminId)
            ->get()
            ->map(function ($item) {
                $lomba = optional(optional($item->pengajuanLombaMahasiswa)->lomba);
                $pengajuan = $item->pengajuanLombaMahasiswa;
                return [
                    'id' => $item->id,
                    'type' => 'pengajuan_lomba',
                    'judul' => $lomba->judul ?? '-',
                    'status' => $pengajuan->status ?? '-',
                    'note' => $pengajuan->note ?? null,
                    'deskripsi' => 'Pengajuan lomba: ' . ($lomba->judul ?? '-'),
                    'created_at' => $item->created_at,
                    'is_accepted' => $item->is_accepted,
                ];
            });

        // Notifikasi pengajuan prestasi untuk admin
        $pengajuanPrestasi = PengajuanPrestasiAdminNote::with(['prestasi'])
            ->where('dosen_id', $adminId)
            ->get()
            ->map(function ($item) {
                $prestasi = optional($item->prestasi);
                return [
                    'id' => $item->id,
                    'type' => 'pengajuan_prestasi',
                    'judul' => $prestasi->judul ?? '-',
                    'status' => $item->status ?? '-', // status langsung dari PengajuanPrestasiAdminNote
                    'note' => $item->note ?? null,
                    'deskripsi' => 'Pengajuan prestasi: ' . ($prestasi->judul ?? '-'),
                    'created_at' => $item->created_at,
                    'is_accepted' => $item->is_accepted,
                ];
            });

        $notifikasi = $pengajuanLomba->concat($pengajuanPrestasi)
            ->sortByDesc(function ($item) {
                return strtotime($item['created_at']);
            })
            ->values()
            ->map(function ($item) {
                $item['created_at'] = \Carbon\Carbon::parse($item['created_at'])->diffForHumans();
                return $item;
            });

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }


    public function markAsRead(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $adminId = auth('dosen')->id();

        if ($type === 'pengajuan_lomba') {
            $notif = PengajuanLombaAdminNote::where('id', $id)
                ->where('dosen_id', $adminId)
                ->first();
        } elseif ($type === 'pengajuan_prestasi') {
            $notif = PengajuanPrestasiAdminNote::where('id', $id)
                ->where('dosen_id', $adminId)
                ->first();
        } else {
            return response()->json(['success' => false, 'message' => 'Tipe notifikasi tidak valid.'], 400);
        }

        if ($notif && !$notif->is_accepted) {
            $notif->is_accepted = true;
            $notif->save();
        }

        if (!$notif) {
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Notifikasi ditandai sebagai dibaca.']);
    }


    public function markAllAsRead()
    {
        $adminId = auth('dosen')->id();

        $countLomba = PengajuanLombaAdminNote::where('dosen_id', $adminId)
            ->where('is_accepted', false)
            ->update(['is_accepted' => true]);

        $countPrestasi = PengajuanPrestasiAdminNote::where('dosen_id', $adminId)
            ->where('is_accepted', false)
            ->update(['is_accepted' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai dibaca.',
            'pengajuan_lomba_updated' => $countLomba,
            'pengajuan_prestasi_updated' => $countPrestasi,
        ]);
    }


    public function destroy(Request $request, $type, $id)
    {
        $adminId = auth('dosen')->id();

        if ($type === 'pengajuan_lomba') {
            $deleted = PengajuanLombaAdminNote::where('id', $id)
                ->where('dosen_id', $adminId)
                ->delete();
        } elseif ($type === 'pengajuan_prestasi') {
            $deleted = PengajuanPrestasiAdminNote::where('id', $id)
                ->where('dosen_id', $adminId)
                ->delete();
        } else {
            return response()->json(['success' => false, 'message' => 'Tipe tidak valid.'], 400);
        }

        return response()->json([
            'success' => $deleted > 0,
            'message' => $deleted ? 'Notifikasi berhasil dihapus.' : 'Notifikasi tidak ditemukan.'
        ]);
    }


    public function destroyIsAccpeptedMessege()
    {
        $adminId = auth('dosen')->id();

        $delLomba = PengajuanLombaAdminNote::where('dosen_id', $adminId)
            ->where('is_accepted', true)
            ->delete();

        $delPrestasi = PengajuanPrestasiAdminNote::where('dosen_id', $adminId)
            ->where('is_accepted', true)
            ->delete();

        return response()->json([
            'success' => true,
            'deleted_pengajuan_lomba' => $delLomba,
            'deleted_pengajuan_prestasi' => $delPrestasi,
            'message' => 'Semua notifikasi yang sudah dibaca telah dihapus.'
        ]);
    }
}
