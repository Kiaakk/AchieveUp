{{-- filepath: d:\Laravel\Fork\AchieveUp\resources\views\admin\notifikasi\detail.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Detail Notifikasi')
@section('content')

<div class="container mx-auto p-6 max-w-2xl">

    {{-- Jika ini notifikasi pengajuan lomba --}}
    @if (isset($pengajuanLomba))
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-2">Detail Pengajuan Lomba</h2>
            <table class="w-full text-sm mb-2">
                <tr>
                    <td class="font-semibold w-40">Judul Lomba</td>
                    <td>: {{ $lomba->judul ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Tempat</td>
                    <td>: {{ $lomba->tempat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Periode</td>
                    <td>:
                        @if ($lomba)
                            {{ \Carbon\Carbon::parse($lomba->tanggal_daftar)->format('d M Y') }}
                            s.d.
                            {{ \Carbon\Carbon::parse($lomba->tanggal_daftar_terakhir)->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">Status Pengajuan</td>
                    <td>
                        :
                        @if ($pengajuan->status === 'approved')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700 border border-green-300">
                                <i class="fas fa-check-circle mr-1"></i> Disetujui
                            </span>
                        @elseif($pengajuan->status === 'rejected')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 border border-red-300">
                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                <i class="fas fa-hourglass-half mr-1"></i> Diproses
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">Catatan</td>
                    <td>: {{ $pengajuan->note ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Waktu Pengajuan</td>
                    <td>: {{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-6 flex space-x-3">
            <a href="{{ url('admin/notifikasi') }}"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">
                Kembali
            </a>
            <a href="{{ url('admin/lomba/pengajuan/' . $pengajuan->id) }}"
                class="px-4 py-2 bg-blue-300 rounded hover:bg-blue-400 transition">
                Detail Lengkap
            </a>
            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition btn-hapus"
                data-id="{{ $pengajuanLomba->id }}" data-type="pengajuan_lomba">
                Hapus
            </button>
        </div>
    @endif

    {{-- Jika ini notifikasi pengajuan prestasi --}}
    @if (isset($pengajuanPrestasi))
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-2">Detail Pengajuan Prestasi</h2>
            <table class="w-full text-sm mb-2">
                <tr>
                    <td class="font-semibold w-40">Judul Prestasi</td>
                    <td>: {{ $prestasi->judul ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Tingkat</td>
                    <td>: {{ ucfirst($prestasi->tingkat ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Status Pengajuan</td>
                    <td>
                        :
                        @if ($pengajuanPrestasi->status === 'approved')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700 border border-green-300">
                                <i class="fas fa-check-circle mr-1"></i> Disetujui
                            </span>
                        @elseif($pengajuanPrestasi->status === 'rejected')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 border border-red-300">
                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                <i class="fas fa-hourglass-half mr-1"></i> Diproses
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">Catatan</td>
                    <td>: {{ $pengajuanPrestasi->note ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Waktu Pengajuan</td>
                    <td>: {{ \Carbon\Carbon::parse($pengajuanPrestasi->created_at)->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-6 flex space-x-3">
            <a href="{{ url('admin/notifikasi') }}"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">
                Kembali
            </a>
            <a href="{{ url('admin/prestasi/' . $prestasi->id) }}"
                class="px-4 py-2 bg-blue-300 rounded hover:bg-blue-400 transition">
                Detail Lengkap
            </a>
            
            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition btn-hapus"
                data-id="{{ $pengajuanPrestasi->id }}" data-type="pengajuan_prestasi">
                Hapus
            </button>
        </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            const type = $(this).data('type');
            Swal.fire({
                title: 'Yakin hapus notifikasi ini?',
                text: "Data yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/notifikasi/${type}/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', response.message, 'success')
                                .then(() => {
                                    window.location.href = "{{ url('admin/notifikasi') }}";
                                });
                        },
                        error: function() {
                            Swal.fire('Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection