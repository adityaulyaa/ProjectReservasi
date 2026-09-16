<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progress: ') . $list->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ $list->name }}</h3>
                    @if($list->description)
                        <p class="text-gray-600 mb-4">{{ $list->description }}</p>
                    @endif

                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="font-medium">Overall Progress</span>
                            <span class="text-gray-600">
                                {{ $list->tasks->where('is_completed', true)->count() }} / {{ $list->tasks->count() }} completed
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="bg-green-600 h-6 rounded-full transition-all duration-300 flex items-center justify-center" 
                                 style="width: {{ $list->progress_percentage }}%">
                                <span class="text-sm text-white font-bold">{{ $list->progress_percentage }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-4 mt-4">
                        <div class="bg-blue-50 p-3 rounded">
                            <p class="text-sm text-gray-600">Total Tasks</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $list->tasks->count() }}</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded">
                            <p class="text-sm text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-green-600">{{ $list->tasks->where('is_completed', true)->count() }}</p>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded">
                            <p class="text-sm text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ $list->tasks->where('is_completed', false)->count() }}</p>
                        </div>
                        <div class="bg-red-50 p-3 rounded">
                            <p class="text-sm text-gray-600">Overdue</p>
                            @php $overdue = $list->tasks->filter(fn($task) => $task->isOverdue())->count(); @endphp
                            <p class="text-2xl font-bold text-red-600">{{ $overdue }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Filter Tasks</h3>

                    <form method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">All</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="incomplete" {{ request('status') === 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                <select name="priority" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">All</option>
                                    <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Urgent" {{ request('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                                <select name="assigned_to" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">All</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ request('assigned_to') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Filter
                        </button>
                    </form>

                    <h3 class="text-lg font-semibold mb-4">Tasks</h3>

                    @if($tasks->isEmpty())
                        <p class="text-gray-500">No tasks found.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($tasks as $task)
                                <div class="border border-gray-200 rounded-lg p-4 
                                    {{ $task->is_completed ? 'bg-gray-50' : ($task->isOverdue() ? 'border-red-500 border-2 bg-red-50' : '') }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" {{ $task->is_completed ? 'checked' : '' }} 
                                                       class="rounded" disabled>
                                                <h4 class="font-semibold {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                                    {{ $task->title }}
                                                </h4>
                                                @if($task->isOverdue())
                                                    <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">OVERDUE</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 ml-6">{{ $task->description }}</p>
                                        </div>
                                        <div class="text-right ml-4">
                                            <span class="inline-block px-3 py-1 rounded text-sm font-medium
                                                @if($task->priority === 'Urgent') bg-red-100 text-red-800
                                                @elseif($task->priority === 'High') bg-orange-100 text-orange-800
                                                @elseif($task->priority === 'Medium') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800
                                                @endif">
                                                {{ $task->priority }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-600 ml-6 mt-2">
                                        Due: {{ $task->due_date ? $task->due_date->format('M d, Y H:i') : 'No due date' }}
                                        | Assigned to: {{ $task->assignee?->name ?? 'Unassigned' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
