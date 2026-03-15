<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2 tracking-tight">
                Dashboard Overview
            </h1>
            <p class="text-slate-500 mt-2 text-sm">Welcome back. Here's what's happening today.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('tasks.create') }}" class="hidden sm:inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm shadow-blue-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Task
            </a>
        @endif
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 group">
        
        <!-- Total Tasks -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Tasks</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $totalTasks }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full opacity-50"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Pending</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $pendingTasks }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full opacity-50"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 bg-green-100 text-green-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Completed</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $completedTasks }}</p>
                </div>
            </div>
        </div>

        <!-- High Priority -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full opacity-50"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 bg-rose-100 text-rose-600 rounded-xl transform -rotate-12">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Urgent</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $highPriorityTasks }}</p>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Charts & Tables Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Chart -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 h-full flex flex-col">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                    Task Status
                </h3>
                <div class="relative w-full aspect-square flex-1 flex items-center justify-center p-4">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Tasks -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 h-full">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Recently Added
                    </h3>
                    <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center">
                        View all <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                
                @if($recentTasks->isEmpty())
                    <div class="py-12 text-center text-slate-500 border border-dashed border-slate-200 rounded-xl bg-slate-50">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p>No tasks found.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                    <th class="py-3 px-4 rounded-tl-lg">Task</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Priority</th>
                                    <th class="py-3 px-4 rounded-tr-lg hidden sm:table-cell">Due Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentTasks as $task)
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="py-3 px-4 align-top">
                                            <div class="sm:flex sm:items-center sm:justify-between">
                                                <div>
                                                    <a href="{{ route('tasks.show', $task) }}" class="font-medium text-slate-900 group-hover:text-blue-600 transition-colors">
                                                        {{ Str::limit($task->title, 40) }}
                                                    </a>
                                                    <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                            {{ $task->assignedUser->name ?? 'Unassigned' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 align-top">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->status->badgeClass() }}">
                                                {{ $task->status->label() }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 align-top">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-transparent {{ $task->priority->badgeClass() }}">
                                                {{ $task->priority->label() }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 align-top text-sm text-slate-600 hidden sm:table-cell">
                                            <div class="flex items-center gap-1.5 {{ $task->due_date->isPast() && $task->status->value !== 'completed' ? 'text-red-600 shrink-0 font-medium' : '' }}">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $task->due_date->format('M d, Y') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
    </div>

    <!-- Initialize Chart.js safely -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statusChart');
            if (ctx) {
                const pending = {{ $statusCounts['pending'] ?? 0 }};
                const inProgress = {{ $statusCounts['in_progress'] ?? 0 }};
                const completed = {{ $statusCounts['completed'] ?? 0 }};
                
                if (pending === 0 && inProgress === 0 && completed === 0) {
                    // Empty state for chart container
                    ctx.parentElement.innerHTML = '<div class="text-center text-slate-400 p-6 flex flex-col items-center"><svg class="w-12 h-12 mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg><p class="text-sm">No data available to build chart</p></div>';
                    return;
                }

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'In Progress', 'Completed'],
                        datasets: [{
                            data: [pending, inProgress, completed],
                            backgroundColor: [
                                '#FDE68A', // amber-200
                                '#BFDBFE', // blue-200
                                '#BBF7D0'  // green-200
                            ],
                            hoverBackgroundColor: [
                                '#F59E0B', // amber-500
                                '#3B82F6', // blue-500
                                '#22C55E'  // green-500
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: { family: "'Inter', sans-serif", size: 12 }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
