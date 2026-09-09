<?php

namespace App\Http\Controllers;

use App\Models\ListModel;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index()
    {
        $lists = ListModel::with(['tasks', 'owner'])
            ->where('user_id', auth()->id())
            ->orWhereHas('members', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->get();

        return view('progress.index', compact('lists'));
    }

    public function show(Request $request, ListModel $list)
    {
        $query = $list->tasks()->with('assignee');

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('is_completed', true);
            } elseif ($request->status === 'incomplete') {
                $query->where('is_completed', false);
            }
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $tasks = $query->get();
        $members = $list->members()->get();

        return view('progress.show', compact('list', 'tasks', 'members'));
    }
}
