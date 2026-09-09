<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * FR-ADM-03: Menampilkan daftar seluruh akun pengguna
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'user'  => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search'));
    }

    /**
     * FR-ADM-01: Menambahkan akun pengguna baru secara manual
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', 'string', Rule::in(['admin', 'user'])],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * FR-ADM-02: Menghapus akun pengguna dari sistem
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun '{$userName}' berhasil dihapus dari sistem.");
    }

    /**
     * FR-ADM-04: Mereset password akun pengguna jika terjadi kendala akses
     */
    public function resetPassword(Request $request, int $id): RedirectResponse
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
