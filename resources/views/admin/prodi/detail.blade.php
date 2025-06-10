@extends('admin.layouts.app')

@section('title', 'Detail Program Studi')

@section('content')
    <div class="max-w-[1280px] mx-auto my-8 px-4 md:px-8">
        <div class="grid grid-cols-12 gap-6 lg:gap-8">
            <!-- Main Content -->
            <div class="col-span-12 lg:col-span-8">
                <!-- Header Card -->
                <div class="rounded-lg shadow-lg overflow-hidden mb-6"
                    style="background: linear-gradient(91deg, #6041CE -0.69%, #513C99 100%);">
                    <div class="p-6 text-white">
                        <div class="flex items-center gap-3 mb-4">
                            <a href="{{ url('admin/prodi') }}"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 hover:bg-white/30 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold">{{ $prodi->nama }}</h1>
                                <p class="text-purple-100 text-sm">Detail program studi</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                <span class="text-sm font-medium">Program Studi</span>
                            </div>
                            <div class="text-sm opacity-75">
                                Kode: {{ $prodi->kode }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Program Studi Information Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden mb-6">
                    <div class="border-b border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Informasi Program Studi
                        </h2>
                    </div>

                    <div class="p-6">
                        <!-- Program Header -->
                        <div class="flex items-start gap-6 mb-8">
                            <div
                                class="w-24 h-24 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center border-4 border-gray-200 shadow-lg flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>

                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $prodi->nama }}</h3>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Program Studi
                                    </span>

                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        Kode: {{ $prodi->kode }}
                                    </span>

                                    @if ($prodi->fakultas)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $prodi->fakultas->nama }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-600">Program studi aktif di sistem manajemen prestasi mahasiswa</p>
                            </div>
                        </div>

                        <!-- Detailed Information -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Detail Informasi</h3>

                            <div class="space-y-6">
                                <!-- Basic Information Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Nama Program Studi</p>
                                                <p class="text-sm font-semibold text-gray-900">{{ $prodi->nama }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Kode Program Studi</p>
                                                <p class="text-sm font-semibold text-gray-900 font-mono">
                                                    {{ $prodi->kode }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Faculty Information Row -->
                                @if ($prodi->fakultas)
                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-purple-600" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 font-medium">Fakultas</p>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ $prodi->fakultas->nama }}</p>
                                                    @if ($prodi->fakultas->kode)
                                                        <p class="text-xs text-gray-500 font-mono">Kode:
                                                            {{ $prodi->fakultas->kode }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Statistics Row -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-orange-600" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 font-medium">Total Mahasiswa</p>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ $prodi->mahasiswa->count() ?? 0 }} Mahasiswa
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-white rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-yellow-600" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 font-medium">Total Prestasi</p>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ $prodi->mahasiswa->sum(function ($mhs) {return $mhs->prestasis->count();}) ?? 0 }}
                                                        Prestasi
                                                    </p>
                                                </div>
                                            </div>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-medium">Status</p>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Aktif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4">
            <!-- Quick Actions Card -->
            <div
                class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg mb-6">
                <div class="p-5" style="background: linear-gradient(91deg, #6041CE -0.69%, #513C99 100%);">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Aksi
                    </h4>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ url("admin/prodi/edit/{$prodi->id}") }}"
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800">Edit Program Studi</h5>
                                <p class="text-sm text-gray-500">Update informasi program studi</p>
                            </div>
                        </a>

                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800">Total Mahasiswa</h5>
                                <p class="text-sm text-gray-500">
                                    {{ $prodi->mahasiswa->count() ?? 0 }} mahasiswa terdaftar
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                            <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800">Total Prestasi</h5>
                                <p class="text-sm text-gray-500">
                                    {{ $prodi->mahasiswa->sum(function ($mhs) {return $mhs->prestasis->count();}) ?? 0 }}
                                    prestasi dicapai
                                </p>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div
            class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg mb-6">
            <div class="p-5 border-b border-gray-100">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistik Prestasi
                </h4>
            </div>
            <div class="p-5">
                <div class="space-y-4">
                    @php
                        $totalPrestasi = $prodi->mahasiswa->sum(function ($mhs) {
                            return $mhs->prestasis->count();
                        });
                        $prestasiDisetujui = $prodi->mahasiswa->sum(function ($mhs) {
                            return $mhs->prestasis->where('status', 'disetujui')->count();
                        });
                        $prestasiPending = $prodi->mahasiswa->sum(function ($mhs) {
                            return $mhs->prestasis->where('status', 'pending')->count();
                        });
                    @endphp

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Prestasi Disetujui</span>
                        <span class="text-sm font-semibold text-green-600">{{ $prestasiDisetujui }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Prestasi Pending</span>
                        <span class="text-sm font-semibold text-yellow-600">{{ $prestasiPending }}</span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t">
                        <span class="text-sm font-medium text-gray-800">Total Prestasi</span>
                        <span class="text-sm font-bold text-blue-600">{{ $totalPrestasi }}</span>
                    </div>

                    @if ($totalPrestasi > 0)
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Tingkat Pencapaian</span>
                                <span>{{ number_format(($prestasiDisetujui / $totalPrestasi) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full"
                                    style="width: {{ ($prestasiDisetujui / $totalPrestasi) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- System Information Card -->
        <div
            class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="p-5 border-b border-gray-100">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Sistem
                </h4>
            </div>
            <div class="p-5">
                <div class="space-y-4 text-sm">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r">
                        <h5 class="font-medium text-blue-800 mb-1">Program Studi</h5>
                        <p class="text-blue-700">Program studi digunakan untuk mengkategorikan mahasiswa dalam
                            sistem prestasi.</p>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <h6 class="font-medium text-gray-800">Fungsi</h6>
                            <ul class="text-gray-600 text-xs mt-1 space-y-1"><li>• Klasifikasi mahasiswa berdasarkan jurusan</li>
                                    <li>• Filter prestasi berdasarkan program studi</li>
                                    <li>• Statistik pencapaian per program</li>
                                    <li>• Laporan kinerja akademik</li>
                                    <li>• Klasifikasi mahasiswa</li>
                                    <li>• Statistik pencapaian per program</li>
                            </ul>
                        </div>

                        @if ($prodi->fakultas)
                            <div>
                                <h6 class="font-medium text-gray-800">Fakultas</h6>
                                <p class="text-gray-600">{{ $prodi->fakultas->nama }}</p>
                                @if ($prodi->fakultas->kode)
                                    <p class="text-xs text-gray-500">Kode: {{ $prodi->fakultas->kode }}</p>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom animations */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Custom hover effects */
        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush
