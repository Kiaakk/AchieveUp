@extends('mahasiswa.layouts.app')

@section('title', 'Lomba')

@section('content')
    <div class="mx-auto max-w-full h-full flex flex-col">

        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-2">
                <label for="show-entry" class="text-sm font-medium text-gray-700">Tampilkan</label>
                <select id="show-entry"
                    class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#6041CE] focus:border-transparent transition-shadow">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="40">40</option>
                </select>
                <span class="text-sm font-medium text-gray-700">data</span>
            </div>
            <div class="flex items-center gap-2">
                <input id="search-bar" type="text" placeholder="Cari..." class="input" />
                <a href="{{ route('mahasiswa.lomba.create') }}" id="btn-add-user" class="button-primary-medium">
                    <i class="fas fa-plus mr-2"></i>
                    <span>Tambah</span>
                </a>
            </div>
        </div>

        <div class="flex-1 overflow-x-auto bg-white shadow rounded border border-gray-200">
            <table id="lomba-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                            Mulai</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pembimbing</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="lomba-body" class="bg-white divide-y divide-gray-200 overflow-y-auto">

                </tbody>
            </table>
            <p id="lomba_info" class="text-sm text-gray-500 mt-2 px-4"></p>
            <div id="lomba_pagination" class="mt-2 flex flex-wrap gap-2 px-4 pb-4"></div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            let lombaData = [];
            let currentPage = 1;

            function actionButtonsLomba(id, status) {
                if (status === 'disetujui') {
                    return `
                        <div class="flex gap-2">
                            <a href="/mahasiswa/lomba/${id}" class="action-button detail-button" title="Detail">
                                <i class="fas fa-eye text-[18px]"></i>
                            </a>
                        </div>
                    `;
                }
                if (status === 'pending') {
                    return `
                        <div class="flex gap-2">
                            <a href="/mahasiswa/lomba/${id}" class="action-button detail-button" title="Detail">
                                <i class="fas fa-eye text-[18px]"></i>
                            </a>
                        </div>
                    `;
                }
                return `
                    <div class="flex gap-2">
                        <a href="/mahasiswa/lomba/${id}" class="action-button detail-button" title="Detail">
                            <i class="fas fa-eye text-[18px]"></i>
                        </a>
                        <a href="/mahasiswa/lomba/${id}/edit" class="action-button edit-button" title="Edit">
                            <i class="fas fa-edit text-[18px]"></i>
                        </a>
                        <button type="button" class="action-button delete-button btn-hapus" data-id="${id}" data-type="lomba" title="Hapus">
                            <i class="fas fa-trash text-[18px]"></i>
                        </button>
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
                            url: `/mahasiswa/lomba/${id}`,
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
                                loadLomba();
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
                $('#lomba-table tbody').css({
                    'max-height': `${entriesToShow * rowHeight}px`
                });
            }

            function renderLombaTable() {
                let searchQuery = $('#search-bar').val().toLowerCase();
                let entriesToShow = parseInt($('#show-entry').val()) || 10;
                let tbody = $('#lomba-body');

                const statusOrder = {
                    'pending': 1,
                    'ditolak': 2,
                    'disetujui': 3
                };
                let sorted = lombaData.slice().sort((a, b) => {
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
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada data ditemukan</td>
                </tr>
            `);
                } else {
                    $.each(paginated, function(index, item) {
                        let pembimbing = item.dosens.map(d => d.nama).join(', ') || '-';
                        let statusClass = item.status === 'disetujui' ? 'bg-green-100 text-green-800' :
                            item.status === 'ditolak' ? 'bg-red-100 text-red-800' :
                            'bg-gray-200 text-gray-900';

                        let row = `
                    <tr class="h-12 hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm">${item.judul}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">${item.tanggal_mulai}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">${item.tempat}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">${pembimbing}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-2.5 px-2 py-1 rounded text-xs font-semibold capitalize
                                ${item.status === 'disetujui' ? 'bg-green-100 text-green-800' : 
                                item.status === 'ditolak' ? 'bg-red-100 text-red-800' : 
                                'bg-gray-200 text-gray-900'}">
                                <i class="${
                                    item.status === 'disetujui' ? 'fas fa-check-circle text-green-500' :
                                    item.status === 'ditolak' ? 'fas fa-times-circle text-red-500' :
                                    'fas fa-hourglass-half text-gray-500'
                                }"></i>
                                ${item.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            ${actionButtonsLomba(item.id, item.status)}
                        </td>
                    </tr>
                `;
                        tbody.append(row);
                    });
                }

                $("#lomba_info").text(`Menampilkan ${paginated.length} dari ${totalData} data`);

                let paginationHtml = '';
                for (let i = 1; i <= totalPages; i++) {
                    paginationHtml +=
                        `<button class="px-3 py-1 rounded-md text-sm ${i === currentPage ? 'bg-[#6041CE] text-white' : 'bg-gray-200'} page-btn-lomba" data-page="${i}">${i}</button>`;
                }
                $("#lomba_pagination").html(paginationHtml);

                $(".page-btn-lomba").off("click").on("click", function() {
                    currentPage = parseInt($(this).data("page"));
                    renderLombaTable();
                });

                setTableHeight(entriesToShow);
            }

            function loadLomba() {
                $.ajax({
                    url: '/mahasiswa/lomba/getdata',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        lombaData = response.data;
                        currentPage = 1;
                        renderLombaTable();
                    },
                    error: function() {
                        alert('Gagal memuat data lomba.');
                    }
                });
            }

            $('#search-bar, #show-entry').on('input change', function() {
                currentPage = 1;
                renderLombaTable();
            });

            loadLomba();
        });
    </script>

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
