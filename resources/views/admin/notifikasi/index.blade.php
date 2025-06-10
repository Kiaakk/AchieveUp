{{-- filepath: d:\Laravel\Fork\AchieveUp\resources\views\admin\notifikasi\index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="container mx-auto max-w-2xl p-4">
        <h2 class="text-xl font-bold mb-4">Notifikasi</h2>
        <div class="flex justify-end gap-2 mb-4 animate-fade-in">
            <button id="mark-all"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition">
                Tandai Semua
            </button>
            <button id="delete-read" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow transition">
                Hapus Semua
            </button>
        </div>

        <div id="notif-list">
            <div class="text-gray-500 text-center py-8" id="notif-loading">Memuat notifikasi...</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function loadNotifikasi() {
                fetch('{{ route('admin.notifikasi.getAll') }}')
                    .then(res => res.json())
                    .then(res => {
                        let html = '';
                        if (res.data && res.data.length > 0) {
                            html += '<ul class="space-y-4 animate-fade-in">';
                            res.data.forEach(item => {
                                let statusText = '';
                                let pesanText = '';
                                let jenis = '';
                                if (item.type === 'pengajuan_lomba') {
                                    jenis = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Pengajuan Lomba
                                        </span>`;
                                    let icon = '';
                                    let statusLabel = '';
                                    let statusClass = '';
                                    if (item.status === 'approved') {
                                        icon = `<i class="fas fa-check-circle text-green-500 mr-1 text-sm"></i>`;
                                        statusLabel = 'Disetujui';
                                        statusClass = 'bg-green-100 text-green-800 border border-green-300';
                                    } else if (item.status === 'rejected') {
                                        icon = `<i class="fas fa-times-circle text-red-500 mr-1 text-sm"></i>`;
                                        statusLabel = 'Ditolak';
                                        statusClass = 'bg-red-100 text-red-800 border border-red-300';
                                    } else {
                                        icon = `<i class="fas fa-hourglass-half text-gray-500 mr-1 text-sm"></i>`;
                                        statusLabel = 'Diproses';
                                        statusClass = 'bg-gray-100 text-gray-700 border border-gray-300';
                                    }
                                    statusText =
                                        `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                            ${icon}${statusLabel}
                                        </span>`;
                                    pesanText =
                                        `<div class="text-xs text-blue-600">${item.note ?? ''}</div>`;
                                } else if (item.type === 'pengajuan_prestasi') {
                                    jenis = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Pengajuan Prestasi
                                        </span>`;
                                    let icon = '';
                                    let statusLabel = '';
                                    let statusClass = '';
                                    if (item.status === 'approved') {
                                        icon = `<i class="fas fa-check-circle text-green-500 mr-1 text-sm"></i>`;
                                        statusLabel = 'Disetujui';
                                        statusClass = 'bg-green-100 text-green-800 border border-green-300';
                                    } else if (item.status === 'rejected') {
                                        icon = `<i class="fas fa-times-circle text-red-500 mr-1 text-sm"></i>`;
                                        statusLabel = 'Ditolak';
                                        statusClass = 'bg-red-100 text-red-800 border border-red-300';
                                    } else {
                                        icon = `<i class="fas fa-hourglass-half text-gray-500 mr-1 text-sm"></i>`;
                                        statusLabel = 'Diproses';
                                        statusClass = 'bg-gray-100 text-gray-700 border border-gray-300';
                                    }
                                    statusText =
                                        `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                            ${icon}${statusLabel}
                                        </span>`;
                                    pesanText =
                                        `<div class="text-xs text-blue-600">${item.note ?? ''}</div>`;
                                }

                                html += `
                                    <li class="notif-item bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition p-4 flex flex-row items-center justify-between gap-4 ${item.is_accepted ? '' : 'border-l-4 border-blue-500'} relative"
                                        data-id="${item.id}" data-type="${item.type}" data-read="${item.is_accepted}" style="cursor:pointer;">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <div class="font-semibold truncate">${item.judul}</div>
                                                ${jenis}
                                            </div>
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <div class="text-sm text-gray-600">${item.created_at}</div>
                                                ${statusText}
                                            </div>
                                            ${pesanText}
                                        </div>
                                        ${!item.is_accepted ? `<span class="ml-2 block w-4 h-4 rounded-full bg-red-600 border-2 border-white"></span>` : ''}
                                    </li>`;
                            });
                            html += '</ul>';
                        } else {
                            html = '<div class="text-gray-500 text-center py-8">Tidak ada notifikasi.</div>';
                        }
                        document.getElementById('notif-list').innerHTML = html;
                    });
            }

            loadNotifikasi();

            document.getElementById('mark-all').addEventListener('click', function() {
                fetch('{{ route('admin.notifikasi.markAllAsRead') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            loadNotifikasi();
                        }
                    });
            });

            document.getElementById('delete-read').addEventListener('click', function() {
                Swal.fire({
                    title: 'Yakin ingin menghapus semua pesan yang sudah dibaca?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('admin.notifikasi.destroyIsAccpeptedMessege') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(res => {
                                if (res.success) {
                                    loadNotifikasi();
                                    Swal.fire('Berhasil!',
                                        'Semua pesan yang sudah dibaca telah dihapus.',
                                        'success');
                                }
                            });
                    }
                });
            });

            document.getElementById('notif-list').addEventListener('click', function(e) {
                const notifItem = e.target.closest('.notif-item');
                if (notifItem) {
                    const id = notifItem.getAttribute('data-id');
                    const type = notifItem.getAttribute('data-type');
                    const isRead = notifItem.getAttribute('data-read') === 'true' || notifItem.getAttribute('data-read') === '1';

                    if (!isRead) {
                        fetch('{{ route('admin.notifikasi.markAsRead') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id,
                                type
                            })
                        }).then(res => res.json()).then(res => {
                            window.location.href = `/admin/notifikasi/${type}/${id}`;
                        });
                    } else {
                        window.location.href = `/admin/notifikasi/${type}/${id}`;
                    }
                }
            });
        });
    </script>
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out;
        }
    </style>
@endsection