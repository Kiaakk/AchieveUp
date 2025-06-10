<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Dosen;
use App\Models\Lomba;
use App\Models\PengajuanLombaAdminNote;
use App\Models\PengajuanLombaMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LombaMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Lomba',
            'list' => ['Home', 'Manajemen Lomba'],
        ];

        $page = (object) [
            'title' => 'Daftar lomba yang terdaftar dalam sistem',
        ];

        $activeMenu = 'lomba';

        return view('mahasiswa.lomba.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function getAll()
    {
        $lombas = Lomba::with('bidang')
            ->where('is_active', true)
            ->get();

        $data = $lombas->map(function ($lomba) {
            $warnaTingkat = match ($lomba->tingkat) {
                'internasional' => 'bg-red-100 text-red-800',
                'nasional' => 'bg-blue-100 text-blue-800',
                'regional' => 'bg-green-100 text-green-800',
                'provinsi' => 'bg-yellow-100 text-yellow-800',
            };

            return [
                'id' => $lomba->id,
                'judul' => $lomba->judul,
                'tempat' => $lomba->tempat,
                'tanggal_daftar' => $lomba->tanggal_daftar,
                'tanggal_daftar_terakhir' => $lomba->tanggal_daftar_terakhir,
                'periode_pendaftaran' => Carbon::parse($lomba->tanggal_daftar)->format('d M Y') .
                    ' s.d. ' .
                    Carbon::parse($lomba->tanggal_daftar_terakhir)->format('d M Y'),
                'link' => $lomba->url,
                'tingkat' => $lomba->tingkat,
                'tingkat_warna' => $warnaTingkat,
                'is_individu' => $lomba->is_individu ? 'Ya' : 'Tidak',
                'is_active' => $lomba->is_active ? 'Ya' : 'Tidak',
                'file_poster' => $lomba->file_poster,
                'is_akademik' => $lomba->is_akademik ? 'Ya' : 'Tidak',
                'bidang' => $lomba->bidang->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'kode' => $b->kode,
                        'nama' => $b->nama,
                    ];
                }),
            ];
        });

        return response()->json($data);
    }

    public function getPengajuan()
    {
        $mahasiswaId = auth()->guard('mahasiswa')->id();

        $pengajuans = PengajuanLombaMahasiswa::with(['lomba.bidang', 'admin'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->get();

        $data = $pengajuans->map(function ($pengajuan) {
            $lomba = $pengajuan->lomba;
            $warnaTingkat = match ($lomba->tingkat) {
                'internasional' => 'bg-red-100 text-red-800',
                'nasional' => 'bg-blue-100 text-blue-800',
                'regional' => 'bg-green-100 text-green-800',
                'provinsi' => 'bg-yellow-100 text-yellow-800',
            };

            $warnaStatus = match ($pengajuan->status) {
                'pending' => 'bg-yellow-100 text-yellow-800',
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
            };

            return [
                'id' => $pengajuan->id,
                'lomba_id' => $lomba->id,
                'judul' => $lomba->judul,
                'tempat' => $lomba->tempat,
                'tanggal_daftar' => $lomba->tanggal_daftar,
                'tanggal_daftar_terakhir' => $lomba->tanggal_daftar_terakhir,
                'periode_pendaftaran' => Carbon::parse($lomba->tanggal_daftar)->format('d M Y') .
                    ' s.d. ' .
                    Carbon::parse($lomba->tanggal_daftar_terakhir)->format('d M Y'),
                'link' => $lomba->url,
                'tingkat' => $lomba->tingkat,
                'tingkat_warna' => $warnaTingkat,
                'is_individu' => $lomba->is_individu ? 'Ya' : 'Tidak',
                'is_active' => $lomba->is_active ? 'Ya' : 'Tidak',
                'file_poster' => $lomba->file_poster,
                'is_akademik' => $lomba->is_akademik ? 'Ya' : 'Tidak',
                'status' => $pengajuan->status,
                'status_warna' => $warnaStatus,
                'notes' => $pengajuan->notes,
                'admin' => $pengajuan->admin ? [
                    'id' => $pengajuan->admin->id,
                    'nama' => $pengajuan->admin->nama,
                ] : null,
                'bidang' => $lomba->bidang->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'kode' => $b->kode,
                        'nama' => $b->nama,
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function destroyPengajuan($id)
    {
        $pengajuan = PengajuanLombaMahasiswa::findOrFail($id);

        if ($pengajuan->mahasiswa_id !== auth()->guard('mahasiswa')->id()) {
            return response()->json(['error' => 'Tidak diizinkan menghapus pengajuan ini'], 403);
        }

        if ($pengajuan->status !== 'pending') {
            return response()->json(['error' => 'Hanya pengajuan berstatus pending yang dapat dihapus'], 400);
        }

        $pengajuan->delete();

        return response()->json(['message' => 'Pengajuan berhasil dihapus']);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Ajukan Lomba Baru',
            'list' => ['Home', 'Manajemen Lomba', 'Ajukan Lomba'],
        ];

        $page = (object) [
            'title' => 'Form pengajuan lomba baru',
        ];

        $activeMenu = 'lomba';

        $bidangs = Bidang::all();

        return view('mahasiswa.lomba.create', compact('breadcrumb', 'page', 'activeMenu', 'bidangs'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal_daftar' => 'required|date|after_or_equal:today',
            'tanggal_daftar_terakhir' => 'required|date|after_or_equal:tanggal_daftar',
            'url' => 'nullable|url|max:255',
            'tingkat' => 'required|in:nasional,internasional,regional,provinsi',
            'is_individu' => 'required|boolean',
            'is_akademik' => 'required|boolean',
            'file_poster' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'bidang' => 'required|array|min:1',
            'bidang.*' => 'exists:bidang,id',
        ], [
            'judul.required' => 'Judul lomba wajib diisi.',
            'tempat.required' => 'Tempat lomba wajib diisi.',
            'tanggal_daftar.required' => 'Tanggal daftar wajib diisi.',
            'tanggal_daftar.after_or_equal' => 'Tanggal daftar harus hari ini atau setelahnya.',
            'tanggal_daftar_terakhir.required' => 'Tanggal daftar terakhir wajib diisi.',
            'tanggal_daftar_terakhir.after_or_equal' => 'Tanggal daftar terakhir harus setelah atau sama dengan tanggal daftar.',
            'url.url' => 'URL pendaftaran harus berupa URL yang valid.',
            'tingkat.required' => 'Tingkat lomba wajib dipilih.',
            'is_individu.required' => 'Jenis peserta wajib dipilih.',
            'is_akademik.required' => 'Jenis kompetisi wajib dipilih.',
            'file_poster.image' => 'File poster harus berupa gambar.',
            'file_poster.mimes' => 'File poster harus berformat JPG atau PNG.',
            'file_poster.max' => 'Ukuran file poster maksimal 5MB.',
            'bidang.required' => 'Minimal satu bidang harus dipilih.',
            'bidang.*.exists' => 'Bidang yang dipilih tidak valid.',
        ]);

        $filePosterPath = null;
        if ($request->hasFile('file_poster')) {
            $filePosterPath = $request->file('file_poster')->store('posters', 'public');
        }

        $lomba = Lomba::create([
            'judul' => $validated['judul'],
            'tempat' => $validated['tempat'],
            'tanggal_daftar' => $validated['tanggal_daftar'],
            'tanggal_daftar_terakhir' => $validated['tanggal_daftar_terakhir'],
            'url' => $validated['url'],
            'tingkat' => $validated['tingkat'],
            'is_individu' => $validated['is_individu'],
            'is_active' => false,
            'file_poster' => $filePosterPath,
            'is_akademik' => $validated['is_akademik'],
        ]);

        $lomba->bidang()->sync($validated['bidang']);

        $pengajuan = PengajuanLombaMahasiswa::create([
            'lomba_id' => $lomba->id,
            'mahasiswa_id' => auth()->guard('mahasiswa')->id(),
            'status' => 'pending',
        ]);

        $adminList = Dosen::where('role', 'admin')->get();

        $data = [];
        foreach ($adminList as $admin) {
            $data[] = [
                'pengajuan_lomba_mahasiswa_id' => $pengajuan->id,
                'dosen_id' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($data)) {
            PengajuanLombaAdminNote::insert($data);
        }
        return redirect()->route('mahasiswa.lomba.index')->with('success', 'Pengajuan lomba berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function show($id)
    {
        if (!Auth::guard('mahasiswa')->check()) {
            abort(403, 'Akses ditolak. Silakan login sebagai mahasiswa.');
        }

        $lomba = Lomba::with(['bidang'])->find($id);

        if (!$lomba) {
            abort(404, 'Lomba tidak ditemukan');
        }

        $mahasiswa = Auth::guard('mahasiswa')->user();

        $pengajuan = PengajuanLombaMahasiswa::where('lomba_id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        $breadcrumb = (object) [
            'title' => 'Detail Lomba',
            'list' => ['Home', 'Lomba', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail Informasi Lomba',
        ];

        $activeMenu = 'lomba';

        return view('mahasiswa.lomba.detaillomba', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'lomba',
            'pengajuan',
            'mahasiswa'
        ));
    }

    public function showPengajuan($id)
    {

        if (!Auth::guard('mahasiswa')->check()) {
            abort(403, 'Akses ditolak. Silakan login sebagai mahasiswa.');
        }

        $mahasiswa = Auth::guard('mahasiswa')->user();

        $pengajuan = PengajuanLombaMahasiswa::with(['lomba.bidang', 'admin'])
            ->where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$pengajuan) {
            abort(404, 'Pengajuan lomba tidak ditemukan atau bukan milik Anda');
        }

        $breadcrumb = (object) [
            'title' => 'Detail Pengajuan Lomba',
            'list' => ['Home', 'Lomba', 'Pengajuan', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail Pengajuan Lomba Anda',
        ];

        $activeMenu = 'lomba';

        return view('mahasiswa.lomba.detailpengajuan', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'pengajuan',
            'mahasiswa'
        ));
    }
}
