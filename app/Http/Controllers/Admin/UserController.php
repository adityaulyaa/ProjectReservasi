<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * FR-ADM-03: Menampilkan daftar seluruh akun pengguna
     */
    public function index(Request $request): View
    {
        $query = User::query();

        $search = $request->input('search');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($status === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'user'  => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search'));
    }

    /**
     * Show the form for creating a new user account.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * FR-ADM-01: Menambahkan akun pengguna baru secara manual
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_verified' => $request->boolean('is_verified', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna '{$user->name}' berhasil ditambahkan!");
    }

    /**
     * Show the form for editing the specified user account.
     */
    public function edit(int|string $id): View
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user account.
     */
    public function update(UpdateUserRequest $request, int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_verified = $request->boolean('is_verified');

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun '{$user->name}' berhasil diperbarui.");
    }

    /**
     * FR-ADM-02: Menghapus akun pengguna dari sistem
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun '{$userName}' berhasil dihapus dari sistem.");
    }

    /**
     * Verify the specified user account.
     */
    public function verify(int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->is_verified = true;
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
        }
        $user->save();

        return back()->with('success', "Akun '{$user->name}' berhasil diverifikasi.");
    }

    /**
     * Reject or unverify the specified user account.
     */
    public function reject(int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->is_verified = false;
        $user->save();

        return back()->with('success', "Status verifikasi akun '{$user->name}' ditolak/dibatalkan.");
    }

    /**
     * FR-ADM-04: Mereset password akun pengguna jika terjadi kendala akses
     */
    public function resetPassword(Request $request, int|string $id): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Password untuk akun '{$user->name}' berhasil direset!");
    }
}
