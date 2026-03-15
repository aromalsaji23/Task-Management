<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('tasks.index') }}" class="hover:text-blue-600 transition-colors pointer-events-auto">Tasks</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Task Details</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                {{ $task->title }}
            </h1>
        </div>
        
        <div class="flex flex-wrap items-center justify-start sm:justify-end gap-3 isolate">
            @if(auth()->user()->can('update', $task))
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 text-sm font-medium rounded-lg border border-slate-300 shadow-sm hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Task
                </a>
            @endif

            @if(auth()->user()->can('delete', $task))
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Are you sure you want to delete this task?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-red-600 text-sm font-medium rounded-lg border border-slate-300 shadow-sm hover:bg-red-50 hover:border-red-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Main Content -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    Description
                </h2>
                <div class="prose max-w-none text-slate-600 whitespace-pre-line">
                    {{ $task->description ?: 'No description provided for this task.' }}
                </div>
            </div>

            <!-- AI Insight Panel -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-6 shadow-sm border border-indigo-100 relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-indigo-200/40 rounded-full blur-3xl"></div>
                <h2 class="text-lg font-bold text-indigo-900 mb-4 flex items-center gap-2 relative z-10">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    AI Task Insights
                </h2>
                
                @if($task->ai_summary)
                    <div class="space-y-4 relative z-10">
                        <div>
                            <p class="text-xs font-semibold text-indigo-800 uppercase tracking-wider mb-1">Generated Summary</p>
                            <p class="text-indigo-900/80 leading-relaxed bg-white/60 p-4 rounded-xl shadow-sm backdrop-blur-sm border border-white">
                                {{ $task->ai_summary }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-semibold text-indigo-800 uppercase tracking-wider mb-2">AI Analyzed Priority</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border bg-white shadow-sm {{ $task->ai_priority?->badgeClass() }}">
                                {{ $task->ai_priority?->label() ?? 'Not determined' }}
                            </span>
                            @if($task->ai_priority && $task->priority->value !== $task->ai_priority->value)
                                <p class="text-xs text-indigo-600 mt-2 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    AI suggests a different priority than currently set.
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white/60 p-4 rounded-xl border border-indigo-100 border-dashed text-indigo-800 text-center relative z-10">
                        <p class="text-sm">AI analysis has not been generated for this task yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Meta Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3">Task Meta</h3>
                
                <div class="space-y-5">
                    <!-- Status -->
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1.5 uppercase tracking-wide">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $task->status->badgeClass() }}">
                            {{ $task->status->label() }}
                        </span>
                    </div>

                    <!-- Priority -->
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1.5 uppercase tracking-wide">Priority</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border border-transparent {{ $task->priority->badgeClass() }}">
                            {{ $task->priority->label() }}
                        </span>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1.5 uppercase tracking-wide">Due Date</p>
                        <div class="flex items-center gap-2 text-slate-800 font-medium bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                            <svg class="w-5 h-5 {{ $task->due_date->isPast() && $task->status->value !== 'completed' ? 'text-red-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="{{ $task->due_date->isPast() && $task->status->value !== 'completed' ? 'text-red-600' : '' }}">
                                {{ $task->due_date->format('F d, Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Assigned User -->
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1.5 uppercase tracking-wide">Assigned To</p>
                        <div class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ substr($task->assignedUser->name ?? 'U', 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $task->assignedUser->name ?? 'Unassigned' }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $task->assignedUser->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="pt-4 border-t border-slate-100 mt-2">
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Created:</span>
                            <span class="text-slate-600 font-medium">{{ $task->created_at?->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400 mt-1.5">
                            <span>Last Updated:</span>
                            <span class="text-slate-600 font-medium">{{ $task->updated_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
