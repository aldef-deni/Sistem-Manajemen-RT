<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ResidentRegistrationController extends Controller
{
    private const SESSION_KEY = 'resident_registration';

    private const VERIFICATION_LIFETIME_MINUTES = 15;

    public function identity(Request $request): View
    {
        $request->session()->forget(self::SESSION_KEY);

        return view('auth.register-resident-identity');
    }

    public function verify(Request $request): RedirectResponse
    {
        $identity = [
            'nik' => preg_replace('/\D/', '', (string) $request->input('nik')),
            'no_kk' => preg_replace('/\D/', '', (string) $request->input('no_kk')),
        ];

        $validator = Validator::make($identity, [
            'nik' => ['required', 'digits:16'],
            'no_kk' => ['required', 'digits:16'],
        ], [
            'nik.digits' => 'NIK harus terdiri dari 16 digit.',
            'no_kk.digits' => 'Nomor KK harus terdiri dari 16 digit.',
        ], [
            'nik' => 'NIK',
            'no_kk' => 'Nomor KK',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $members = AnggotaKeluarga::query()
            ->with('kartuKeluarga:id,no_kk')
            ->kepalaKeluarga()
            ->where('nik', $identity['nik'])
            ->whereHas('kartuKeluarga', fn ($query) => $query->where('no_kk', $identity['no_kk']))
            ->limit(2)
            ->get();

        if ($members->count() !== 1) {
            return back()->withErrors([
                'identity' => 'NIK dan Nomor KK tidak cocok dengan data Kepala Keluarga yang terdaftar. Silakan hubungi pengurus RT.',
            ]);
        }

        $member = $members->first();

        if (User::where('anggota_keluarga_id', $member->id)->exists()) {
            return redirect()->route('login')->with('status', 'Data Kepala Keluarga tersebut sudah memiliki akun. Silakan masuk atau hubungi pengurus RT bila lupa akun.');
        }

        $request->session()->put(self::SESSION_KEY, [
            'member_id' => $member->id,
            'verified_at' => now()->timestamp,
        ]);
        $request->session()->regenerateToken();

        return redirect()->route('register.resident.account');
    }

    public function account(Request $request): View|RedirectResponse
    {
        $member = $this->verifiedMember($request);

        if (! $member) {
            return redirect()->route('register.resident.identity')
                ->withErrors(['identity' => 'Verifikasi identitas telah berakhir. Silakan verifikasi NIK dan Nomor KK kembali.']);
        }

        return view('auth.register-resident-account', compact('member'));
    }

    public function store(Request $request): RedirectResponse
    {
        $member = $this->verifiedMember($request);

        if (! $member) {
            return redirect()->route('register.resident.identity')
                ->withErrors(['identity' => 'Verifikasi identitas telah berakhir. Silakan verifikasi NIK dan Nomor KK kembali.']);
        }

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        try {
            $user = DB::transaction(function () use ($member, $validated) {
                $lockedMember = AnggotaKeluarga::query()->lockForUpdate()->findOrFail($member->id);

                if (! $lockedMember->isKepalaKeluarga() || User::where('anggota_keluarga_id', $lockedMember->id)->exists()) {
                    return null;
                }

                return User::create([
                    'name' => $lockedMember->nama_lengkap,
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['no_hp'] ?? $lockedMember->no_hp,
                    'role' => 'warga',
                    'anggota_keluarga_id' => $lockedMember->id,
                    'password' => Hash::make($validated['password']),
                ]);
            });
        } catch (QueryException $exception) {
            // Constraint database menjadi pertahanan terakhir untuk dua proses
            // pendaftaran yang mencoba memakai data warga yang sama bersamaan.
            if (! User::where('anggota_keluarga_id', $member->id)->exists()) {
                throw $exception;
            }

            $user = null;
        }

        if (! $user) {
            $request->session()->forget(self::SESSION_KEY);

            return redirect()->route('login')->with('status', 'Data Kepala Keluarga tersebut sudah memiliki akun. Silakan masuk atau hubungi pengurus RT.');
        }

        $request->session()->forget(self::SESSION_KEY);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Akun Warga berhasil dibuat dan sudah terhubung dengan data Kepala Keluarga Anda.');
    }

    private function verifiedMember(Request $request): ?AnggotaKeluarga
    {
        $verification = $request->session()->get(self::SESSION_KEY);

        if (! is_array($verification)
            || ! isset($verification['member_id'], $verification['verified_at'])
            || (int) $verification['verified_at'] < now()->subMinutes(self::VERIFICATION_LIFETIME_MINUTES)->timestamp) {
            $request->session()->forget(self::SESSION_KEY);

            return null;
        }

        $member = AnggotaKeluarga::query()
            ->with('kartuKeluarga:id,no_kk')
            ->find($verification['member_id']);

        if (! $member
            || ! $member->isKepalaKeluarga()
            || User::where('anggota_keluarga_id', $member->id)->exists()) {
            $request->session()->forget(self::SESSION_KEY);

            return null;
        }

        return $member;
    }
}
