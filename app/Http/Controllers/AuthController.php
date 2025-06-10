<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Cek login untuk mahasiswa
        if (
            Auth::guard('mahasiswa')->attempt(['nim' => $username, 'password' => $password]) ||
            Auth::guard('mahasiswa')->attempt(['username' => $username, 'password' => $password])
        ) {
            $request->session()->regenerate();
            $request->session()->put('login_time', now());
            return redirect()->route('mahasiswa.dashboard.index')
                ->with('user', Auth::guard('mahasiswa')->user());
        }

        // Cek login untuk dosen
        if (
            Auth::guard('dosen')->attempt(['nidn' => $username, 'password' => $password]) ||
            Auth::guard('dosen')->attempt(['username' => $username, 'password' => $password])
        ) {
            $request->session()->regenerate();
            $request->session()->put('login_time', now());
            $user = Auth::guard('dosen')->user();

            if ($user->role === 'admin') {
                Log::info('Admin logged in', ['user_id' => $user->id]);
                return redirect()->route('admin.dashboard.index')
                    ->with('user', $user);
            } elseif (in_array($user->role, ['dosen', 'dosen pembimbing'])) {
                return redirect()->route('dosen.dashboard.index')
                    ->with('user', $user);
            } else {
                Auth::guard('dosen')->logout();
                return back()->with('error', 'Role dosen tidak valid.');
            }
        }

        return back()->with('error', 'Username atau password tidak cocok.');
    }

    public function logout(Request $request)
    {
        Auth::guard('mahasiswa')->logout();
        Auth::guard('dosen')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showRegisterMahasiswa()
    {
        $programStudis = ProgramStudi::all();
        return view('auth.login', compact('programStudis'));
    }

    public function registerMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:mahasiswa,nim',
            'nama' => 'required|string',
            'username' => 'required|string|unique:mahasiswa,username',
            'email' => 'required|email|unique:mahasiswa,email',
            'program_studi_id' => 'required|exists:program_studi,id',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ]);

        $mahasiswa = Mahasiswa::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => null,
            'program_studi_id' => $request->program_studi_id,
        ]);

        return redirect('/login')
            ->with('success', 'Registrasi berhasil. Silakan login dengan akun Anda.');
    }

    public function showForgotPassword()
    {
        return view('auth.login');
    }

    public function cekUserInput(Request $request)
    {
        $input = $request->input('user_input');

        $mahasiswa = Mahasiswa::where('nim', $input)
            ->orWhere('username', $input)
            ->orWhere('nama', $input)
            ->first();

        $dosen = Dosen::where('nidn', $input)
            ->orWhere('username', $input)
            ->orWhere('nama', $input)
            ->first();

        if ($mahasiswa) {
            return redirect('/ganti_password')
                ->with('success', 'Akun ditemukan. Silakan lanjutkan proses ganti password.')
                ->with('user_type', 'mahasiswa')
                ->with('user_data', [
                    'id' => $mahasiswa->id,
                    'nim' => $mahasiswa->nim,
                    'username' => $mahasiswa->username,
                    'nama' => $mahasiswa->nama,
                ]);
        } elseif ($dosen) {
            return redirect('/ganti_password')
                ->with('success', 'Akun ditemukan. Silakan lanjutkan proses ganti password.')
                ->with('user_type', 'dosen')
                ->with('user_data', [
                    'id' => $dosen->id,
                    'nidn' => $dosen->nidn,
                    'username' => $dosen->username,
                    'nama' => $dosen->nama,
                ]);
        } else {
            return redirect('/forgot_password')
                ->with('error', 'Data tidak ditemukan. Silakan cek kembali input Anda.');
        }
    }

    public function showGantiPassword()
    {
        return view('auth.login');
    }


    public function simpanPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_type' => 'required|in:mahasiswa,dosen',
            'id' => 'required|integer',
            'password_input' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ], [
            'password_input.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_input.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('user_type', $request->user_type)
                ->with('user_data', ['id' => $request->id]);
        }

        $userType = $request->input('user_type');
        $id = $request->input('id');
        $passwordBaru = $request->input('password_input');

        if ($userType === 'mahasiswa') {
            $user = Mahasiswa::find($id);
        } else {
            $user = Dosen::find($id);
        }

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->password = Hash::make($passwordBaru);
        $user->save();

        return redirect('/login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
