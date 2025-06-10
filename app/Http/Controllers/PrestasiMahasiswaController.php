<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengajuanPrestasiAdminNote;
use App\Models\Prestasi;
use App\Models\PrestasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PrestasiMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Prestasi',
            'list' => ['Home', 'Prestasi']
        ];

        $page = (object)[
            'title' => 'Daftar prestasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'prestasi';

        return view('mahasiswa.prestasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function getData()
    {
        $mahasiswaId = auth()->guard('mahasiswa')->id();

        $prestasis = Prestasi::whereHas('mahasiswas', function ($query) use ($mahasiswaId) {
            $query->where('mahasiswa_id', $mahasiswaId);
        })->with(['dosens'])->get();

        return response()->json([
            'data' => $prestasis
        ]);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Pengajuan Prestasi',
            'list' => ['Home', 'Pengajuan Prestasi']
        ];

        $page = (object) [
            'title' => 'Pengajuan Prestasi'
        ];

        $activeMenu = 'prestasi';

        $mahasiswas = Mahasiswa::all(['id', 'nama', 'nim']);
        $dosens = Dosen::where('role', 'dosen pembimbing')->get(['id', 'nama', 'nidn']);
        $bidangs = Bidang::all(['id', 'nama', 'kode']);

        return view('mahasiswa.prestasi.create', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'dosens',
            'mahasiswas',
            'bidangs'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pengajuan' => 'required|date',
            'judul' => 'required',
            'tempat' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'tingkat' => 'required|in:nasional,internasional,regional,provinsi',
            'juara' => 'required|in:1,2,3',
            'is_individu' => 'required|boolean',
            'is_akademik' => 'required|boolean',
            'url' => 'nullable|url',
            'nomor_surat_tugas' => 'required',
            'tanggal_surat_tugas' => 'required|date',
            'file_surat_tugas' => 'required|mimes:pdf|max:2048',
            'file_sertifikat' => 'required|mimes:pdf|max:2048',
            'file_poster' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'foto_kegiatan' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'dosen_pembimbing' => 'required|array|min:1',
            'dosen_pembimbing.*' => 'exists:dosen,id',
            'mahasiswas' => 'nullable|array',
            'mahasiswas.*' => 'exists:mahasiswa,id',
            'bidang' => 'required|exists:bidang,id',
        ]);

        $suratTugasPath = $request->file('file_surat_tugas')->store('asset_prestasi', 'public');
        $sertifikatPath = $request->file('file_sertifikat')->store('asset_prestasi', 'public');
        $posterPath = $request->hasFile('file_poster') ? $request->file('file_poster')->store('asset_prestasi', 'public') : null;
        $fotoKegiatanPath = $request->hasFile('foto_kegiatan') ? $request->file('foto_kegiatan')->store('asset_prestasi', 'public') : null;

        $prestasi = Prestasi::create([
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'judul' => $request->judul,
            'tempat' => $request->tempat,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tingkat' => $request->tingkat,
            'juara' => $request->juara,
            'is_individu' => $request->is_individu,
            'is_akademik' => $request->is_akademik,
            'url' => $request->url,
            'nomor_surat_tugas' => $request->nomor_surat_tugas,
            'tanggal_surat_tugas' => $request->tanggal_surat_tugas,
            'file_surat_tugas' => $suratTugasPath,
            'file_sertifikat' => $sertifikatPath,
            'file_poster' => $posterPath,
            'foto_kegiatan' => $fotoKegiatanPath,
            'status' => 'pending',
        ]);


        $adminList = Dosen::where('role', 'admin')->get();

        $data = [];
        foreach ($adminList as $admin) {
            $data[] = [
                'prestasi_id' => $prestasi->id,
                'dosen_id' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($data)) {
            PengajuanPrestasiAdminNote::insert($data);
        }

        $prestasi->bidangs()->sync([$request->bidang]);

        $prestasi->dosens()->sync($request->dosen_pembimbing);

        if ($request->filled('mahasiswas')) {
            $prestasi->mahasiswas()->sync($request->mahasiswas);
        } else {
            $prestasi->mahasiswas()->sync([Auth::guard('mahasiswa')->id()]);
        }

        return redirect()->route('mahasiswa.prestasi.index')->with('success', 'Prestasi berhasil diajukan.');
    }

    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        $mahasiswaId = auth()->guard('mahasiswa')->id();
        if (!$prestasi->mahasiswas->contains($mahasiswaId)) {
            abort(403, 'Tidak diizinkan menghapus data ini.');
        }

        $prestasi->dosens()->detach();
        $prestasi->mahasiswas()->detach();

        Storage::disk('public')->delete([$prestasi->file_surat_tugas, $prestasi->file_sertifikat, $prestasi->file_poster, $prestasi->foto_kegiatan]);

        $prestasi->delete();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $breadcrumb = (object)[
            'title' => 'Detail Prestasi',
            'list' => ['Home', 'Prestasi', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail Prestasi'
        ];

        $activeMenu = 'prestasi';

        $prestasi = Prestasi::with(['dosens', 'mahasiswas'])->findOrFail($id);

        $mahasiswaId = auth()->guard('mahasiswa')->id();
        if (!$prestasi->mahasiswas->contains($mahasiswaId)) {
            abort(403, 'Tidak diizinkan mengakses data ini.');
        }

        return view('mahasiswa.prestasi.detail', compact('breadcrumb', 'page', 'activeMenu', 'prestasi'));
    }

    public function edit($id)
    {
        $breadcrumb = (object)[
            'title' => 'Update Prestasi Ditolak',
            'list' => ['Home', 'Prestasi', 'Update']
        ];

        $page = (object)[
            'title' => 'Update Prestasi Ditolak'
        ];

        $activeMenu = 'prestasi';

        $prestasi = Prestasi::with(['notes.dosen', 'bidangs', 'mahasiswas', 'dosens'])->findOrFail($id);
        $mahasiswaId = auth()->guard('mahasiswa')->id();
        if (!$prestasi->mahasiswas->contains($mahasiswaId)) {
            abort(403, 'Tidak diizinkan mengakses data ini.');
        }

        $bidangs = Bidang::all(['id', 'nama', 'kode']);
        $mahasiswas = Mahasiswa::select('id', 'nama', 'nim')->get();
        $dosens = Dosen::select('id', 'nama', 'nidn')->get();

        $existingFileName = $prestasi->foto_kegiatan;
        $existingFilePath = 'storage/' . $existingFileName;

        $existingPosterName = $prestasi->file_poster;
        $existingPosterPath = 'storage/' . $existingPosterName;

        $existingFileSuratTugas = $prestasi->file_surat_tugas;
        $existingFileSuratTugasPath = 'storage/' . $existingFileSuratTugas;

        $existingFileSertifikat = $prestasi->file_sertifikat;
        $existingFileSertifikatPath = 'storage/' . $existingFileSertifikat;

        return view('mahasiswa.prestasi.update', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'prestasi',
            'bidangs',
            'mahasiswas',
            'dosens',
            'existingFileName',
            'existingFilePath',
            'existingPosterName',
            'existingPosterPath',
            'existingFileSuratTugas',
            'existingFileSuratTugasPath',
            'existingFileSertifikat',
            'existingFileSertifikatPath'
        ));
    }

    public function update(Request $request, $id)
    {
        $prestasi = Prestasi::with(['mahasiswas', 'dosens'])->findOrFail($id);

        $mahasiswaId = auth()->guard('mahasiswa')->id();
        if (!$prestasi->mahasiswas->contains($mahasiswaId)) {
            abort(403, 'Tidak diizinkan mengubah data ini.');
        }

        $request->validate([
            'judul' => 'required',
            'tempat' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'tingkat' => 'required|in:nasional,internasional,regional,provinsi',
            'juara' => 'required|in:1,2,3',
            'is_individu' => 'required|boolean',
            'is_akademik' => 'required|boolean',
            'url' => 'nullable|url',
            'nomor_surat_tugas' => 'required',
            'tanggal_surat_tugas' => 'required|date',
            'file_surat_tugas' => [
                'nullable',
                'mimes:pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($prestasi) {
                    if (!$value && !$prestasi->file_surat_tugas) {
                        $fail('File surat tugas diperlukan.');
                    }
                },
            ],
            'file_sertifikat' => [
                'nullable',
                'mimes:pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($prestasi) {
                    if (!$value && !$prestasi->file_sertifikat) {
                        $fail('File sertifikat diperlukan.');
                    }
                },
            ],
            'file_poster' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'foto_kegiatan' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'dosen_pembimbing' => 'required|array|min:1|max:3',
            'dosen_pembimbing.*' => 'exists:dosen,id',
            'mahasiswas' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($request, $mahasiswaId) {
                    $maxMahasiswa = 5;
                    if ($request->is_individu && count($value) > 1) {
                        $fail('Pada mode individu, hanya satu mahasiswa yang diperbolehkan.');
                    }
                    if (!$request->is_individu && count($value) > $maxMahasiswa) {
                        $fail("Maksimum $maxMahasiswa mahasiswa dapat dipilih pada mode kelompok.");
                    }
                    if (!in_array($mahasiswaId, $value)) {
                        $fail('Mahasiswa yang login harus termasuk dalam daftar.');
                    }
                },
            ],
            'mahasiswas.*' => 'exists:mahasiswa,id',
            'bidang' => 'required|exists:bidang,id',
        ]);

        $prestasi->judul = $request->judul;
        $prestasi->tempat = $request->tempat;
        $prestasi->tanggal_mulai = $request->tanggal_mulai;
        $prestasi->tanggal_selesai = $request->tanggal_selesai;
        $prestasi->tingkat = $request->tingkat;
        $prestasi->juara = $request->juara;
        $prestasi->is_individu = $request->is_individu;
        $prestasi->is_akademik = $request->is_akademik;
        $prestasi->url = $request->url;
        $prestasi->nomor_surat_tugas = $request->nomor_surat_tugas;
        $prestasi->tanggal_surat_tugas = $request->tanggal_surat_tugas;

        if ($request->hasFile('file_surat_tugas')) {
            if ($prestasi->file_surat_tugas) {
                Storage::disk('public')->delete($prestasi->file_surat_tugas);
            }
            $prestasi->file_surat_tugas = $request->file('file_surat_tugas')->store('asset_prestasi', 'public');
        }
        if ($request->hasFile('file_sertifikat')) {
            if ($prestasi->file_sertifikat) {
                Storage::disk('public')->delete($prestasi->file_sertifikat);
            }
            $prestasi->file_sertifikat = $request->file('file_sertifikat')->store('asset_prestasi', 'public');
        }
        if ($request->hasFile('file_poster')) {
            if ($prestasi->file_poster) {
                Storage::disk('public')->delete($prestasi->file_poster);
            }
            $prestasi->file_poster = $request->file('file_poster')->store('asset_prestasi', 'public');
        }
        if ($request->hasFile('foto_kegiatan')) {
            if ($prestasi->file_foto_kegiatan) {
                Storage::disk('public')->delete($prestasi->file_foto_kegiatan);
            }
            $prestasi->file_foto_kegiatan = $request->file('foto_kegiatan')->store('asset_prestasi', 'public');
        }

        $prestasi->status = 'pending';
        $prestasi->save();

        $prestasi->bidangs()->sync([$request->bidang]);

        $prestasi->dosens()->sync($request->dosen_pembimbing);

        if ($request->filled('mahasiswas')) {
            $prestasi->mahasiswas()->sync($request->mahasiswas);
        } else {
            $prestasi->mahasiswas()->sync([$mahasiswaId]);
        }

        return redirect()->route('mahasiswa.prestasi.index')->with('success', 'Prestasi berhasil diperbarui dan menunggu verifikasi ulang.');
    }
}
