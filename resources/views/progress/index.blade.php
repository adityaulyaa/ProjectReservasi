<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progress Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">My Lists Progress</h3>

                    @if($lists->isEmpty())
                        <p class="text-gray-500">No lists available.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($lists as $list)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-lg">{{ $list->name }}</h4>
                                            <p class="text-sm text-gray-600">Owner: {{ $list->owner->name }}</p>
                                        </div>
                                        <a href="{{ route('progress.show', $list->id) }}" 
                                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                            View Details
                                        </a>
                                    </div>

                                    @if($list->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ $list->description }}</p>
                                    @endif

                                    <div class="mb-2">
                                        <div class="flex items-center justify-between text-sm mb-1">
                                            <span class="font-medium">Progress</span>
                                            <span class="text-gray-600">
                                                {{ $list->tasks->where('is_completed', true)->count() }} / {{ $list->tasks->count() }} tasks completed
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4">
                                            <div class="bg-green-600 h-4 rounded-full transition-all duration-300" 
                                                 style="width: {{ $list->progress_percentage }}%">
                                                <span class="flex items-center justify-center h-full text-xs text-white font-semibold">
                                                    {{ $list->progress_percentage }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 text-sm text-gray-600 mt-3">
                                        <span>Total Tasks: {{ $list->tasks->count() }}</span>
                                        <span>Completed: {{ $list->tasks->where('is_completed', true)->count() }}</span>
                                        <span>Pending: {{ $list->tasks->where('is_completed', false)->count() }}</span>
                                        @php
                                            $overdue = $list->tasks->filter(fn($task) => $task->isOverdue())->count();
                                        @endphp
                                        @if($overdue > 0)
                                            <span class="text-red-600 font-semibold">⚠ {{ $overdue }} Overdue</span>
                                        @endif
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
