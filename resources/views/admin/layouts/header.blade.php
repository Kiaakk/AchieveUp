@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\PengajuanPrestasiAdminNote;
    use App\Models\PengajuanLombaAdminNote;

    $adminId = Auth::guard('dosen')->id();

    $unreadPengajuanPrestasi = PengajuanPrestasiAdminNote::where('dosen_id', $adminId)
        ->where('is_accepted', false)
        ->count();

    $unreadPengajuanLomba = PengajuanLombaAdminNote::where('dosen_id', $adminId)->where('is_accepted', false)->count();

    $notifUnread = $unreadPengajuanPrestasi + $unreadPengajuanLomba;
@endphp

<header class="fixed top-0 left-0 right-0 z-50 bg-white shadow px-8 py-4 flex items-center justify-between">
    <div class="text-base font-medium text-gray-700">
    </div>
    
    <div class="flex items-center space-x-3 justify-end relative">
        <a href="{{ url('admin/notifikasi') }}"
            class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition mr-2"
            title="Notifikasi">
            <i class="fas fa-bell text-gray-600"></i>
            @if ($notifUnread > 0)
                <span
                    class="absolute top-2 right-2 block w-2.5 h-2.5 rounded-full bg-red-600 border-2 border-white"></span>
            @endif
        </a>
        <div class="flex flex-col text-right leading-tight">
            <span
                class="text-sm font-medium text-gray-800">{{ Auth::guard('dosen')->user()->nama ?? 'Nama Pengguna' }}</span>
            <span class="text-xs text-gray-500">{{ Auth::guard('dosen')->user()->nidn ?? '-' }}</span>
        </div>
        <div x-data="{ open: false }" class="relative">
            <img src="{{ Auth::guard('dosen')->user() && Auth::guard('dosen')->user()->foto ? asset(Auth::guard('dosen')->user()->foto) : asset('images/default-user.png') }}"
                onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';" alt="User Image"
                class="w-10 h-10 rounded-[12px] object-cover border-2 border-primary cursor-pointer transition duration-200 hover:scale-105"
                @click="open = !open">
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 py-2 ring-1 ring-black/5">
                <a href="{{ route('admin.profil.index') }}">
                    <div class="px-4 py-3 border-b">
                        <div class="flex items-center gap-3 mb-2">
                            <img src="{{ Auth::guard('dosen')->user() && Auth::guard('dosen')->user()->foto ? asset(Auth::guard('dosen')->user()->foto) : asset('images/default-user.png') }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';"
                                alt="User Image"
                                class="w-10 h-10 rounded-[12px] object-cover border-2 border-primary cursor-pointer transition duration-200 hover:scale-105"
                                @click="open = !open">
                            <div>
                                <div class="font-semibold text-md text-gray-800 truncate">
                                    {{ Auth::guard('dosen')->user()->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500 truncate">
                                    {{ Auth::guard('dosen')->user()->email ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </a>
                <form id="logout-form" method="GET" action="{{ route('logout') }}">
                    <button type="button" id="btn-logout"
                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition rounded-b-xl">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</header>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnLogout = document.getElementById('btn-logout');
        if (btnLogout) {
            btnLogout.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil logout',
                            text: 'Anda telah keluar.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            document.getElementById('logout-form').submit();
                        });
                    }
                });
            });
        }
    });
</script>
