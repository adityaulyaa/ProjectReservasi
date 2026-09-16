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
     * Display a listing of user accounts.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
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

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user account.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user account.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_verified' => $request->boolean('is_verified', true),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User account created successfully.');
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

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully.');
    }

    /**
     * Remove the specified user account from storage.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted successfully.');
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

        return back()->with('success', "User '{$user->name}' account verified successfully.");
    }

    /**
     * Reject or unverify the specified user account.
     */
    public function reject(int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->is_verified = false;
        $user->save();

        return back()->with('success', "User '{$user->name}' account verification rejected.");
    }
}
