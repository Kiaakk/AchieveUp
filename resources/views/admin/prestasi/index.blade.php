@extends('admin.layouts.app')

@section('title', 'Verifikasi Prestasi')

@section('content')
    <div class="mx-auto max-w-full h-full flex flex-col">
        <!-- Tab-like header -->
        <div class="flex border-b-0">
            <div
                class="inline-block px-5 py-2.5 text-sm font-medium bg-white border-t border-l border-r border-gray-200 rounded-t-lg text-[#6041CE] font-semibold">
                Verifikasi Prestasi
            </div>
        </div>

        <!-- Controls section -->
        <div class="bg-white border-t border-l border-r border-gray-200 px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <label for="show-entry" class="text-sm font-medium text-gray-700">Tampilkan</label>
                    <select id="show-entry"
                        class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#6041CE] focus:border-transparent transition-all">
                        <option value="5" selected>5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="40">40</option>
                    </select>
                    <span class="text-sm font-medium text-gray-700">data</span>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none md:min-w-[240px]">
                        <input id="search-bar" type="text" placeholder="Cari..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#6041CE] focus:border-transparent transition-all" />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <select id="filter-status"
                            class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#6041CE] focus:border-transparent transition-all">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2 ml-auto">
                        <button
                            id="btn-export"
                            class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center gap-1.5 font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Export Data</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table container -->
        <div class="overflow-hidden bg-white shadow-md border border-gray-200 rounded-b-lg">
            <div class="overflow-x-auto">
                <table id="prestasi-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tempat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pembimbing</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="prestasi-body" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination controls -->
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <p id="prestasi_info" class="text-sm text-gray-600"></p>
                    <div id="prestasi_pagination" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let prestasiData = [];
                let currentPage = 1;

                function getStatusConfig(status) {
                    const config = {
                        'disetujui': {
                            bgClass: 'bg-green-100',
                            textClass: 'text-green-800',
                            icon: 'fas fa-check-circle text-green-500'
                        },
                        'ditolak': {
                            bgClass: 'bg-red-100',
                            textClass: 'text-red-800',
                            icon: 'fas fa-times-circle text-red-500'
                        },
                        'pending': {
                            bgClass: 'bg-yellow-100',
                            textClass: 'text-yellow-800',
                            icon: 'fas fa-hourglass-half text-yellow-500'
                        }
                    };

                    return config[status] || config.pending;
                }

                function actionButtonsPrestasi(id, status) {
                    if (status === 'pending') {
                        return `
                        <div class="flex items-center space-x-3">
                            <a href="/admin/prestasi/${id}" class="action-btn text-blue-600 hover:text-blue-800" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="action-btn text-green-600 hover:text-green-800 approve-button" data-id="${id}" title="Setujui">
                                <i class="fas fa-check-circle"></i>
                            </button>
                            <button type="button" class="action-btn text-red-600 hover:text-red-800 reject-button" data-id="${id}" title="Tolak">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    `;
                    } else if (status === 'ditolak') {
                        return `
                        <div class="flex items-center space-x-3">
                            <a href="/admin/prestasi/${id}" class="action-btn text-blue-600 hover:text-blue-800" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="action-btn text-green-600 hover:text-green-800 approve-button" data-id="${id}" title="Setujui">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </div>
                    `;
                    } else if (status === 'disetujui') {
                        return `
                        <div class="flex items-center space-x-3">
                            <a href="/admin/prestasi/${id}" class="action-btn text-blue-600 hover:text-blue-800" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    `;
                    }
                }

                $(document).on('click', '.approve-button', function() {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin ingin menyetujui prestasi ini?',
                        text: 'Prestasi ini akan disetujui dan mahasiswa terkait akan mendapat notifikasi.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, setujui!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/admin/prestasi/${id}/approve`,
                                type: 'PATCH',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message ||
                                            'Prestasi berhasil disetujui',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    loadPrestasi();
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: xhr.responseJSON?.message ||
                                            'Terjadi kesalahan saat menyetujui prestasi.',
                                    });
                                }
                            });
                        }
                    });
                });

                $(document).on('click', '.reject-button', function() {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin ingin menolak prestasi ini?',
                        icon: 'warning',
                        input: 'textarea',
                        inputLabel: 'Catatan Penolakan',
                        inputPlaceholder: 'Masukkan alasan penolakan...',
                        inputAttributes: {
                            'aria-label': 'Catatan Penolakan',
                            'required': 'required'
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, tolak!',
                        cancelButtonText: 'Batal',
                        preConfirm: (note) => {
                            if (!note || note.trim() === '') {
                                Swal.showValidationMessage('Catatan penolakan wajib diisi.');
                                return false;
                            }
                            return note;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/admin/prestasi/${id}/reject`,
                                type: 'PATCH',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    note: result.value
                                },
                                success: function(res) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message ||
                                            'Prestasi berhasil ditolak',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    loadPrestasi();
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: xhr.responseJSON?.message ||
                                            'Terjadi kesalahan saat menolak prestasi.',
                                    });
                                }
                            });
                        }
                    });
                });

                function renderPrestasiTable() {
                    let searchQuery = $('#search-bar').val().toLowerCase();
                    let statusFilter = $('#filter-status').val();
                    let entriesToShow = parseInt($('#show-entry').val()) || 5;
                    let tbody = $('#prestasi-body');

                    // First sort by status priority (pending first), then filter by search and status
                    let filtered = prestasiData.sort((a, b) => {
                        if (a.status === 'pending' && b.status !== 'pending') return -1;
                        if (a.status !== 'pending' && b.status === 'pending') return 1;
                        return 0;
                    }).filter(item => {
                        const matchesSearch =
                            item.judul.toLowerCase().includes(searchQuery) ||
                            item.tempat.toLowerCase().includes(searchQuery) ||
                            (item.mahasiswas && item.mahasiswas.some(m => m.nama.toLowerCase().includes(
                                searchQuery))) ||
                            (item.dosens && item.dosens.some(d => d.nama.toLowerCase().includes(searchQuery)));

                        const matchesStatus = !statusFilter || item.status === statusFilter;

                        return matchesSearch && matchesStatus;
                    });

                    let totalData = filtered.length;
                    let totalPages = Math.ceil(totalData / entriesToShow);
                    let startIndex = (currentPage - 1) * entriesToShow;
                    let paginated = filtered.slice(startIndex, startIndex + entriesToShow);

                    tbody.empty();

                    if (paginated.length === 0) {
                        tbody.append(`
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-award text-gray-300 text-5xl mb-3"></i>
                                    <p class="text-lg font-medium mb-1">Tidak ada data ditemukan</p>
                                    <p class="text-sm text-gray-400">Tidak ada prestasi yang perlu diverifikasi saat ini</p>
                                </div>
                            </td>
                        </tr>
                    `);
                    } else {
                        $.each(paginated, function(index, item) {
                            let pembimbing = item.dosens.map(d => d.nama).join(', ') || '-';
                            let mahasiswa = item.mahasiswas && item.mahasiswas.length > 0 ?
                                item.mahasiswas.map(m => m.nama).join(', ') :
                                '-';

                            const statusConfig = getStatusConfig(item.status);

                            let row = `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <div class="truncate max-w-[200px]" title="${item.judul}">${item.judul}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">${item.tanggal_mulai}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="truncate max-w-[150px]" title="${item.tempat}">${item.tempat}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="truncate max-w-[150px]" title="${mahasiswa}">${mahasiswa}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="truncate max-w-[150px]" title="${pembimbing}">${pembimbing}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium capitalize
                                        ${statusConfig.bgClass} ${statusConfig.textClass}">
                                        <i class="${statusConfig.icon}"></i>
                                        ${item.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${actionButtonsPrestasi(item.id, item.status)}
                                </td>
                            </tr>
                        `;
                            tbody.append(row);
                        });
                    }

                    $("#prestasi_info").text(`Menampilkan ${paginated.length} dari ${totalData} data prestasi`);

                    let paginationHtml = '';
                    if (totalPages > 1) {
                        for (let i = 1; i <= totalPages; i++) {
                            paginationHtml += `
                            <button class="px-3 py-1.5 rounded text-sm font-medium transition-colors duration-200
                                ${i === currentPage ? 'bg-[#6041CE] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'} 
                                page-btn-prestasi" data-page="${i}">${i}
                            </button>
                        `;
                        }
                    }

                    $("#prestasi_pagination").html(paginationHtml);

                    $(".page-btn-prestasi").off("click").on("click", function() {
                        currentPage = parseInt($(this).data("page"));
                        renderPrestasiTable();
                    });
                }

                function loadPrestasi() {
                    $.ajax({
                        url: '/admin/prestasi/getdata',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            prestasiData = response.data;
                            currentPage = 1;
                            renderPrestasiTable();
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal memuat data prestasi.',
                                timer: 2000
                            });
                        }
                    });
                }

                // Event listeners
                $('#search-bar').on('input', function() {
                    currentPage = 1;
                    renderPrestasiTable();
                });

                $('#show-entry, #filter-status').on('change', function() {
                    currentPage = 1;
                    renderPrestasiTable();
                });

                
                $('#btn-export').on('click', function () {
                    window.open('/admin/prestasi/export', '_blank');
                });

                loadPrestasi();
            });
        </script>
    @endpush


    <style>
        .action-btn {
            @apply inline-flex items-center justify-center h-8 w-8 rounded-full transition-colors duration-200;
        }
    </style>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: true
                });
            });
        </script>
    @endif
@endsection
