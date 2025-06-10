@extends('mahasiswa.layouts.app')

@section('title', 'Prestasi')

@section('content')
    <div class="mx-auto max-w-full h-full flex flex-col">
        <!-- Tab-like header + content container -->
        <div>
            <!-- Header styled as a tab -->
            <div
                class="inline-block px-5 py-2.5 text-sm font-medium bg-white border-t border-l border-r border-gray-200 rounded-t-lg text-[#6041CE] font-semibold">
                Data Prestasi
            </div>

            <!-- Controls section -->
            <div class="bg-white border-t border-l border-r border-gray-200 px-6 py-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <label for="show-entry" class="text-sm font-medium text-gray-700">Tampilkan</label>
                        <select id="show-entry"
                            class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#6041CE] focus:border-transparent transition-all">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
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
                        <a href="{{ route('mahasiswa.prestasi.create') }}" id="btn-add-prestasi"
                            class="flex items-center justify-center px-4 py-2 bg-[#6041CE] hover:bg-[#4e35a5] text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Tambah</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table container -->
            <div class="overflow-hidden bg-white shadow-md border border-gray-200 rounded-b-lg">
                <div class="overflow-x-auto">
                    <table id="prestasi-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tempat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pembimbing</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="prestasi-body" class="bg-white divide-y divide-gray-200 overflow-y-auto">
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
    </div>

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
                        bgClass: 'bg-gray-200',
                        textClass: 'text-gray-800',
                        icon: 'fas fa-hourglass-half text-gray-500'
                    }
                };

                return config[status] || config.pending;
            }

            function actionButtonsPrestasi(id, status) {
                const commonButtons = `
                    <a href="/mahasiswa/prestasi/${id}" class="action-btn text-blue-600 hover:text-blue-800" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                `;

                const editDeleteButtons = `
                    <a href="/mahasiswa/prestasi/${id}/edit" class="action-btn text-amber-600 hover:text-amber-800" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="action-btn text-red-600 hover:text-red-800 btn-hapus" 
                        data-id="${id}" data-type="prestasi" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                `;

                return `
                    <div class="flex items-center gap-4">
                        ${commonButtons}
                        ${status === 'disetujui' || status === 'pending' ? '' : editDeleteButtons}
                    </div>
                `;
            }

            $(document).on('click', '.btn-hapus', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak dapat dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/mahasiswa/prestasi/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data berhasil dihapus.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadPrestasi();
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus data.',
                                });
                            }
                        });
                    }
                });
            });

            function setTableHeight(entriesToShow) {
                const rowHeight = 48;
                const headerHeight = 40;
                const tableHeight = entriesToShow * rowHeight + headerHeight;
                $('#prestasi-table tbody').css({
                    'max-height': `${entriesToShow * rowHeight}px`
                });
            }

            function renderPrestasiTable() {
                let searchQuery = $('#search-bar').val().toLowerCase();
                let entriesToShow = parseInt($('#show-entry').val()) || 10;
                let tbody = $('#prestasi-body');

                const statusOrder = {
                    'pending': 1,
                    'ditolak': 2,
                    'disetujui': 3
                };

                let sorted = prestasiData.slice().sort((a, b) => {
                    return (statusOrder[a.status] || 99) - (statusOrder[b.status] || 99);
                });

                let filtered = sorted.filter(item =>
                    item.judul.toLowerCase().includes(searchQuery) ||
                    item.tempat.toLowerCase().includes(searchQuery)
                );

                let totalData = filtered.length;
                let totalPages = Math.ceil(totalData / entriesToShow);
                let startIndex = (currentPage - 1) * entriesToShow;
                let paginated = filtered.slice(startIndex, startIndex + entriesToShow);

                tbody.empty();

                if (paginated.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-search text-gray-300 text-3xl mb-2"></i>
                                    <p>Tidak ada data ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    `);
                } else {
                    $.each(paginated, function(index, item) {
                        let pembimbing = item.dosens.map(d => d.nama).join(', ') || '-';
                        const statusConfig = getStatusConfig(item.status);

                        let row = `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">${item.judul}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${item.tanggal_mulai}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${item.tempat}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${pembimbing}</td>
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

                $("#prestasi_info").text(`Menampilkan ${paginated.length} dari ${totalData} data`);

                let paginationHtml = '';
                if (totalPages > 1) {
                    
                    if (lombaCurrentPage > 1) {
                        paginationHtml += `
                            <button class="px-3 py-1.5 rounded text-sm font-medium bg-gray-200 hover:bg-gray-300 text-gray-700 page-btn-lomba" 
                                data-page="${lombaCurrentPage - 1}" aria-label="Previous page">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        `;
                    }

                    if (totalPages <= 5) {
                        for (let i = 1; i <= totalPages; i++) {
                            paginationHtml += `
                                <button class="px-3 py-1.5 rounded text-sm font-medium transition-colors duration-200
                                    ${i === lombaCurrentPage ? 'bg-[#6041CE] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'} 
                                    page-btn-lomba" data-page="${i}">${i}
                                </button>
                            `;
                        }
                    } else {
                        if (lombaCurrentPage > 2) {
                            paginationHtml += `
                                <button class="px-3 py-1.5 rounded text-sm font-medium bg-gray-200 hover:bg-gray-300 text-gray-700 page-btn-lomba" 
                                    data-page="1">1</button>
                            `;

                            if (lombaCurrentPage > 3) {
                                paginationHtml += `<span class="px-2 py-1.5 text-gray-500">...</span>`;
                            }
                        }

                        for (let i = Math.max(1, lombaCurrentPage - 1); i <= Math.min(totalPages, lombaCurrentPage +
                                1); i++) {
                            paginationHtml += `
                                <button class="px-3 py-1.5 rounded text-sm font-medium transition-colors duration-200
                                    ${i === lombaCurrentPage ? 'bg-[#6041CE] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'} 
                                    page-btn-lomba" data-page="${i}">${i}
                                </button>
                            `;
                        }

                        if (lombaCurrentPage < totalPages - 1) {
                            if (lombaCurrentPage < totalPages - 2) {
                                paginationHtml += `<span class="px-2 py-1.5 text-gray-500">...</span>`;
                            }

                            paginationHtml += `
                                <button class="px-3 py-1.5 rounded text-sm font-medium bg-gray-200 hover:bg-gray-300 text-gray-700 page-btn-lomba" 
                                    data-page="${totalPages}">${totalPages}</button>
                            `;
                        }
                    }

                    if (lombaCurrentPage < totalPages) {
                        paginationHtml += `
                            <button class="px-3 py-1.5 rounded text-sm font-medium bg-gray-200 hover:bg-gray-300 text-gray-700 page-btn-lomba" 
                                data-page="${lombaCurrentPage + 1}" aria-label="Next page">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        `;
                    }
                }

                $("#prestasi_pagination").html(paginationHtml);

                $(".page-btn-prestasi").off("click").on("click", function() {
                    currentPage = parseInt($(this).data("page"));
                    renderPrestasiTable();
                });

                setTableHeight(entriesToShow);
            }

            function loadPrestasi() {
                $.ajax({
                    url: '/mahasiswa/prestasi/getdata',
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
                        });
                    }
                });
            }

            $('#search-bar').on('input', function() {
                currentPage = 1;
                renderPrestasiTable();
            });

            $('#show-entry').on('change', function() {
                currentPage = 1;
                renderPrestasiTable();
            });

            loadPrestasi();
        });
    </script>

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
