<?php

namespace App\Http\Controllers;

use App\Models\ProjectList;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of personal and collaboration lists.
     */
    public function index(Request $request): View
    {
        $ownedLists = $request->user()->ownedLists()->withCount('members')->latest()->get();
        $collaborationLists = $request->user()->lists()->with('owner')->withCount('members')->latest()->get();

        return view('lists.index', compact('ownedLists', 'collaborationLists'));
    }

    /**
     * Show the form for creating a new list.
     */
    public function create(): View
    {
        return view('lists.create');
    }

    /**
     * Store a newly created list in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $list = $request->user()->ownedLists()->create($validated);

        return redirect()->route('lists.show', $list)
            ->with('success', 'Daftar / proyek berhasil dibuat.');
    }

    /**
     * Display the specified list.
     */
    public function show(ProjectList $list): View
    {
        $this->authorize('view', $list);

        $list->load(['owner', 'members']);

        return view('lists.show', compact('list'));
    }

    /**
     * Show the form for editing the specified list.
     */
    public function edit(ProjectList $list): View
    {
        $this->authorize('update', $list);

        return view('lists.edit', compact('list'));
    }

    /**
     * Update the specified list in storage.
     */
    public function update(Request $request, ProjectList $list): RedirectResponse
    {
        $this->authorize('update', $list);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $list->update($validated);

        return redirect()->route('lists.show', $list)
            ->with('success', 'Daftar / proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified list from storage.
     */
    public function destroy(ProjectList $list): RedirectResponse
    {
        $this->authorize('delete', $list);

        $list->delete();

        return redirect()->route('lists.index')
            ->with('success', 'Daftar / proyek berhasil dihapus.');
    }

    /**
     * Add a member to the list using email or username.
     */
    public function addMember(Request $request, ProjectList $list): RedirectResponse
    {
        $this->authorize('addMember', $list);

        $validated = $request->validate([
            'identifier' => ['required', 'string'],
        ]);

        $targetUser = User::where('email', $validated['identifier'])
            ->orWhere('name', $validated['identifier'])
            ->first();

        if (! $targetUser) {
            return back()->withErrors(['identifier' => 'Pengguna dengan email atau nama tersebut tidak ditemukan.']);
        }

        if ($targetUser->id === $list->owner_id) {
            return back()->withErrors(['identifier' => 'Pengguna ini adalah pemilik daftar.']);
        }

        if ($list->members()->where('user_id', $targetUser->id)->exists()) {
            return back()->withErrors(['identifier' => 'Pengguna ini sudah menjadi anggota daftar.']);
        }

        $list->members()->attach($targetUser->id);

        return back()->with('success', "Member {$targetUser->name} berhasil ditambahkan.");
    }

    /**
     * Remove a member from the list.
     */
    public function removeMember(ProjectList $list, User $user): RedirectResponse
    {
        $this->authorize('removeMember', $list);

        if ($user->id === $list->owner_id) {
            return back()->withErrors(['error' => 'Pemilik daftar tidak dapat dikeluarkan dari anggota.']);
        }

        $list->members()->detach($user->id);

        return back()->with('success', "Member {$user->name} berhasil dikeluarkan dari daftar.");
    }
}
