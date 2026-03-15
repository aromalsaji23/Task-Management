<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task List - Dashboard</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #273041; /* Dark dashboard background */
            color: #ffffff;
        }
        /* Hide scrollbar for clean look */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        .task-card {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .filter-input {
            border-radius: 8px;
        }
    </style>
</head>
<body class="min-h-screen">

<div class="max-w-[1440px] mx-auto px-6 py-8 md:p-10">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold tracking-tight">Task List</h1>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('tasks.create') }}" class="bg-[#3b82f6] hover:bg-blue-600 transition-colors text-white px-5 py-2.5 rounded-lg font-medium text-sm flex items-center shadow-lg shadow-blue-500/20">
                <span class="mr-2 text-lg leading-none">+</span> New Task
            </a>
        @endif
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap gap-4 mb-4 items-center w-full max-w-4xl">
        <!-- Search -->
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search Filter Task" class="filter-input w-full pl-10 pr-4 py-2 bg-white text-gray-800 border-0 focus:ring-2 focus:ring-blue-500 text-sm font-medium h-10 shadow-sm" onchange="this.form.submit()">
        </div>

        <!-- Status -->
        <div class="relative w-full sm:w-40">
            <select name="status" class="filter-input w-full pl-4 pr-10 py-2 bg-white text-gray-800 border-0 appearance-none focus:ring-2 focus:ring-blue-500 text-sm font-medium h-10 shadow-sm" onchange="this.form.submit()">
                <option value="">Status</option>
                @foreach(\App\Enums\TaskStatusEnum::cases() as $status)
                    <option value="{{ $status->value }}" {{ ($filters['status'] ?? '') === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <!-- Assigned User -->
        <div class="relative w-full sm:w-44">
            <select name="assigned_to" class="filter-input w-full pl-4 pr-10 py-2 bg-white text-gray-800 border-0 appearance-none focus:ring-2 focus:ring-blue-500 text-sm font-medium h-10 shadow-sm" onchange="this.form.submit()">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ ($filters['assigned_to'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <!-- Priority -->
        <div class="relative w-full sm:w-40">
            <select name="priority" class="filter-input w-full pl-4 pr-10 py-2 bg-white text-gray-800 border-0 appearance-none focus:ring-2 focus:ring-blue-500 text-sm font-medium h-10 shadow-sm" onchange="this.form.submit()">
                <option value="">Priority</option>
                @foreach(\App\Enums\TaskPriorityEnum::cases() as $priority)
                    <option value="{{ $priority->value }}" {{ ($filters['priority'] ?? '') === $priority->value ? 'selected' : '' }}>{{ $priority->label() }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
        
        <!-- Hidden button for form submit on enter -->
        <button type="submit" class="hidden">Filter</button>
    </form>

    <div class="mb-8">
        <p class="text-[13px] text-gray-400 font-medium">Filter User Task</p>
    </div>

    <!-- Main Grid Layout -->
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left Area: Task Cards -->
        <div class="flex-1 w-full flex flex-col">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                @forelse($tasks as $task)
                    <div class="task-card bg-white p-5 text-gray-800 flex flex-col h-full border border-gray-100">
                        
                        <!-- Top Row: Icon, Status text, Dots -->
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shadow-sm shrink-0">
                                   <!-- Check icon -->
                                   <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                </div>
                                <span class="text-[12px] font-bold text-gray-700 bg-gray-100/80 px-2.5 py-0.5 rounded-full">
                                    {{ $task->status->label() }}
                                </span>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 font-serif text-xl leading-none tracking-widest pt-1" title="Options">
                                &hellip;
                            </button>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-[17px] font-bold text-gray-900 mb-3 leading-snug line-clamp-1">
                            {{ $task->title }}
                        </h3>
                        
                        <!-- Badges -->
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-[6px] uppercase tracking-wide">Status</span>
                            
                            @php
                                $priorityColor = 'bg-blue-500';
                                if($task->priority->value === 'high') $priorityColor = 'bg-[#f43f5e]'; // rose-500
                                if($task->priority->value === 'medium') $priorityColor = 'bg-amber-500';
                            @endphp
                            <span class="text-[10px] font-bold text-white {{ $priorityColor }} px-3 py-1 rounded-[10px] uppercase tracking-wide shadow-sm">
                                Priority: {{ $task->priority->label() }}
                            </span>
                        </div>
                        
                        <!-- Description/AI Summary -->
                        <div class="text-[12px] text-gray-500 mb-5 leading-relaxed {{ $task->ai_summary ? 'bg-gray-50 p-3 rounded-xl border border-gray-100' : 'line-clamp-3' }}">
                            @if($task->ai_summary)
                                <span class="font-semibold text-gray-700 block mb-0.5 text-[10px] uppercase tracking-wider">AI Summary:</span>
                                {{ Str::limit($task->ai_summary, 120) }}
                            @elseif($task->description)
                                {{ Str::limit($task->description, 120) }}
                            @else
                                No description provided for this task.
                            @endif
                        </div>
                        
                        <!-- Metadata -->
                        <div class="text-[12px] text-gray-500 mb-5 space-y-1 font-medium flex-1">
                            <p>Assigned to: <span class="text-gray-700">{{ $task->assignedUser->name ?? 'Unassigned' }}</span></p>
                            <p>Due: <span class="text-gray-700">{{ $task->due_date->format('Y-m-d') }}</span></p>
                            <p>AI Priority: <span class="text-gray-700">{{ $task->ai_priority?->label() ?? 'N/A' }}</span></p>
                        </div>
                        
                        <!-- Bottom Action Row -->
                        <div class="flex justify-between items-center pt-2 mt-auto">
                            <!-- Priority Label mapping at bottom left matching mockup design -->
                            <span class="text-[14px] font-bold text-blue-500 tracking-wide">{{ $task->priority->label() }}</span>
                            
                            <div class="flex gap-2">
                                @if(auth()->user()->can('update', $task))
                                    <a href="{{ route('tasks.edit', $task) }}" class="px-5 py-1.5 bg-gray-100/80 text-gray-700 font-bold rounded-full text-[13px] hover:bg-gray-200 transition text-center border border-transparent">
                                        Edit
                                    </a>
                                @endif
                                <a href="{{ route('tasks.show', $task) }}" class="px-5 py-1.5 bg-[#3b82f6] text-white font-bold rounded-full text-[13px] hover:bg-blue-600 transition text-center shadow-md shadow-blue-500/20">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 py-10 text-center text-gray-400 bg-[#313b51] rounded-2xl border border-[#3f4a61] shadow-inner">
                        <svg class="w-12 h-12 mx-auto text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <p class="text-lg font-medium text-gray-300">No tasks found</p>
                        <p class="mt-1 text-sm">Try adjusting your filters.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tasks->hasPages())
                <div class="mt-8 flex justify-center pb-8">
                    <style>
                        .dark-pagination nav div, .dark-pagination p { color: #cbd5e1 !important; }
                        .dark-pagination .bg-white { background-color: transparent !important; border-color: #3f4a61 !important; color: white !important; }
                        .dark-pagination .bg-white:hover { background-color: #313b51 !important; }
                        .dark-pagination span[aria-current="page"] > span { background-color: #3b82f6 !important; border-color: #3b82f6 !important; color: white !important; }
                        .dark-pagination svg { color: white; }
                    </style>
                    <div class="dark-pagination w-full">
                        {{ $tasks->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Sidebars Area -->
        <div class="w-full lg:w-[320px] flex flex-col gap-6 shrink-0 xl:w-[340px]">
            
            <!-- Context Sidebar 1 (User / Stats) -->
            <div class="bg-white rounded-[16px] text-gray-800 shadow-xl overflow-hidden pt-6">
                
                <!-- Avatar block -->
                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="w-14 h-14 rounded-full bg-[#e2e8f0] flex items-center justify-center text-gray-700 overflow-hidden mb-3 border-4 border-white shadow-sm">
                       <!-- placeholder avatar image or text -->
                       <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-3.333 0-10 1.667-10 5v3h20v-3c0-3.333-6.667-5-10-5z"/></svg>
                    </div>
                    <div class="text-center">
                        <h2 class="font-bold text-lg text-gray-900 leading-tight">{{ auth()->user()->name }}</h2>
                    </div>
                </div>

                <!-- Navigation List -->
                <div class="flex flex-col w-full text-[15px] font-medium border-t border-gray-100">
                    <a href="{{ route('tasks.index') }}" class="py-3.5 px-6 bg-[#3b82f6] text-white w-full text-left">
                        Tasks
                    </a>
                    <a href="{{ route('dashboard') }}" class="py-3.5 px-6 text-gray-700 bg-white border-b border-gray-100 hover:bg-gray-50 block transition-colors">
                        Dashboard
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('dashboard') }}" class="py-3.5 px-6 text-gray-700 bg-white border-b border-gray-100 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                            Users <span class="text-[11px] text-gray-400 font-normal font-sans tracking-wide">(Only visible to Admin)</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left py-3.5 px-6 text-gray-700 bg-white hover:bg-gray-50 transition-colors">Logout</button>
                    </form>
                </div>

                <!-- Circular Charts Area -->
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <!-- Chart 1 -->
                        <div class="flex flex-col items-center">
                            <div class="relative w-14 h-14 mb-2">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <!-- Full 100% blue -->
                                    <path class="text-[#3b82f6]" stroke-width="4" stroke-linecap="round" stroke-dasharray="100, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800">150</div>
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest text-center whitespace-nowrap">Trust Tasks</span>
                        </div>
                        
                        <!-- Chart 2 -->
                        <div class="flex flex-col items-center">
                            <div class="relative w-14 h-14 mb-2">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <!-- 60% gray -->
                                    <path class="text-gray-300" stroke-width="4" stroke-linecap="round" stroke-dasharray="60, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800">90</div>
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest text-center whitespace-nowrap">Completed</span>
                        </div>
                        
                        <!-- Chart 3 -->
                        <div class="flex flex-col items-center">
                            <div class="relative w-14 h-14 mb-2">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <!-- 80% light gray -->
                                    <path class="text-gray-200" stroke-width="4" stroke-linecap="round" stroke-dasharray="80, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800">80</div>
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest text-center whitespace-nowrap">In Progress</span>
                        </div>
                    </div>
                
                    <p class="text-xs text-center font-bold text-gray-900 mb-4 mt-2 tracking-wide">Monthly Task Completion</p>
                    
                    <!-- Internal small graph -->
                    <div class="flex justify-between items-end h-12 px-6">
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[30%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[60%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[80%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[100%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[20%]"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-gray-400 font-bold px-4 pt-2 uppercase tracking-wide">
                        <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span>
                    </div>
                </div>
            </div>

            <!-- Dark Graph Card -->
            <div class="bg-[#242c3d] rounded-[16px] p-6 text-white shadow-xl flex flex-col items-center">
                <h3 class="text-sm font-bold tracking-wide self-start mb-6">Monthly Task Completion</h3>
                
                <div class="relative w-full h-32 mb-2">
                    <!-- Y-axis -->
                    <div class="absolute left-0 top-0 bottom-6 w-8 flex flex-col justify-between text-[11px] text-gray-400 font-medium z-10">
                        <span>180</span>
                        <span>16</span>
                        <span>8</span>
                        <span>0</span>
                    </div>
                    
                    <!-- X-axis Labels -->
                    <div class="absolute left-8 right-0 bottom-0 flex justify-between text-[11px] text-gray-400 font-medium px-2">
                        <span>Jan</span>
                        <span>Feb</span>
                        <span>Mar</span>
                        <span>Apr</span>
                        <span>May</span>
                    </div>

                    <!-- Bars Area -->
                    <div class="absolute left-10 right-2 top-2 bottom-6 flex justify-between items-end border-b border-gray-700 pb-1">
                        <div class="w-[18px] bg-[#3b82f6] rounded-t-[2px] h-[35%] transition-all hover:brightness-125"></div>
                        <div class="w-[18px] bg-[#3b82f6] rounded-t-[2px] h-[85%] transition-all hover:brightness-125"></div>
                        <div class="w-[18px] bg-[#3b82f6] rounded-t-[2px] h-[25%] transition-all hover:brightness-125"></div>
                        <div class="w-[18px] bg-[#3b82f6] rounded-t-[2px] h-[75%] transition-all hover:brightness-125"></div>
                        <div class="w-[18px] bg-[#3b82f6] rounded-t-[2px] h-[100%] transition-all hover:brightness-125"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
