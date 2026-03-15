<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Task - Dashboard</title>
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
        
        .main-card {
            border-radius: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .form-input-pill {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 0.75rem 1.5rem;
            color: #1e293b;
            font-weight: 500;
            width: 100%;
        }
        .form-input-pill:focus {
            outline: none;
            border-color: #3b82f6;
            ring: 2px;
            ring-color: #3b82f6;
        }
        .priority-btn {
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            color: #64748b;
            background-color: #fff;
        }
        .priority-btn.active-low { background-color: #3b82f6; color: white; border-color: #3b82f6; }
        .priority-btn.active-medium { background-color: #f59e0b; color: white; border-color: #f59e0b; }
        .priority-btn.active-high { background-color: #ef4444; color: white; border-color: #ef4444; }
    </style>
</head>
<body class="min-h-screen">

<div class="max-w-[1440px] mx-auto px-6 py-8 md:p-10">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold tracking-tight">Edit Task</h1>
        <a href="{{ route('tasks.create') }}" class="bg-[#3b82f6] hover:bg-blue-600 transition-colors text-white px-5 py-2.5 rounded-lg font-medium text-sm flex items-center shadow-lg shadow-blue-500/20">
            <span class="mr-2 text-lg leading-none">+</span> New Task
        </a>
    </div>

    <!-- Filter Bar (Static in mockup) -->
    <div class="flex flex-wrap gap-4 mb-4 items-center w-full max-w-4xl">
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" placeholder="Search Filter Task" class="w-full pl-10 pr-4 py-2 bg-white rounded-lg text-gray-800 border-0 text-sm font-medium h-10 shadow-sm" readonly>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg text-gray-800 text-sm font-medium h-10 flex items-center gap-2 shadow-sm">
            Status <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg text-gray-800 text-sm font-medium h-10 flex items-center gap-2 shadow-sm">
            All Meedech <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg text-gray-800 text-sm font-medium h-10 flex items-center gap-2 shadow-sm">
            Priority <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </div>

    <div class="mb-8">
        <p class="text-[13px] text-gray-400 font-medium italic">Filter User Task</p>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Left Area: Edit Form Card -->
        <div class="flex-1 w-full flex flex-col items-center">
            <div class="main-card bg-white w-full max-w-3xl overflow-hidden flex flex-col">
                
                <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-8 sm:p-12">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex justify-end mb-4">
                         <button type="button" class="text-gray-300 font-serif text-2xl tracking-widest leading-none">&hellip;</button>
                    </div>

                    <!-- Task Title (Large Display) -->
                    <div class="mb-10">
                        <h2 class="text-[28px] font-extrabold text-slate-800 leading-tight mb-8">{{ $task->title }}</h2>
                        
                        <!-- Title Input Pill -->
                        <div class="relative group">
                            <input type="text" name="title" value="{{ old('title', $task->title) }}" placeholder="e.g. Launch New Campaign" class="form-input-pill pr-12 text-lg" required>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center overflow-hidden">
                                <svg class="w-5 h-5 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-3.333 0-10 1.667-10 5v3h20v-3c0-3.333-6.667-5-10-5z"/></svg>
                            </div>
                        </div>
                        @error('title') <p class="text-red-500 text-xs mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description Box -->
                    <div class="mb-8 p-6 bg-slate-50 rounded-[20px] border border-slate-100 shadow-inner">
                        <textarea name="description" rows="3" class="w-full bg-transparent border-none focus:ring-0 text-slate-600 text-sm leading-relaxed placeholder:text-slate-300 resize-none" placeholder="Describe your task details here...">{{ old('description', $task->description) }}</textarea>
                    </div>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                    <!-- Main Form Grid -->
                    <div class="space-y-6">
                        
                        <!-- Priority Section -->
                        <div class="flex flex-col gap-4">
                            <label class="text-lg font-bold text-slate-800">Priority</label>
                            
                            <!-- Static looking selector matched from mockup -->
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex border border-slate-200 rounded-lg overflow-hidden h-9 shadow-sm">
                                    <button type="button" onclick="setPriority('low')" id="btn-low" class="px-5 text-sm font-semibold {{ $task->priority->value === 'low' ? 'bg-blue-500 text-white' : 'bg-white text-slate-400' }} border-r border-slate-200">Low</button>
                                    <button type="button" onclick="setPriority('low')" class="px-5 text-sm font-semibold bg-blue-400 text-white">Low</button>
                                    <button type="button" onclick="setPriority('medium')" class="px-5 text-sm font-semibold bg-white text-slate-300 border-x border-slate-200">Medium</button>
                                    <button type="button" onclick="setPriority('high')" class="px-5 text-sm font-semibold bg-white text-slate-300 flex items-center gap-1">High <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                                </div>
                            </div>
                            
                            <!-- Large Radio Selection Pills -->
                            <div class="flex flex-wrap gap-3">
                                <input type="hidden" name="priority" id="priority-input" value="{{ old('priority', $task->priority->value) }}">
                                
                                <button type="button" onclick="setPriority('low')" class="priority-pill flex items-center justify-center px-8 py-2.5 rounded-full text-sm font-bold bg-slate-100 text-slate-500 border border-transparent transition-all" data-value="low">Low</button>
                                <button type="button" onclick="setPriority('medium')" class="priority-pill flex items-center justify-center px-8 py-2.5 rounded-full text-sm font-bold bg-slate-100 text-slate-500 border border-transparent transition-all" data-value="medium">Medium</button>
                                <button type="button" onclick="setPriority('medium')" class="priority-pill flex items-center justify-center px-8 py-2.5 rounded-full text-sm font-bold bg-rose-50 text-rose-500 border border-rose-100 transition-all pointer-events-none">+ Medium</button>
                                <button type="button" onclick="setPriority('high')" class="priority-pill flex items-center justify-center px-8 py-2.5 rounded-full text-sm font-bold bg-gray-50 text-gray-400 border border-gray-100 transition-all" data-value="high">+ High</button>
                            </div>
                        </div>

                        <!-- Due Date Pill -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="text" name="due_date" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}" placeholder="Due 2024-12-31" class="form-input-pill text-slate-400 bg-slate-50/50 border-slate-100 placeholder:text-slate-300" onfocus="(this.type='date')" onblur="(this.type='text')">
                        </div>

                        <!-- Assign To Section -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-bold text-slate-800">Assign To</label>
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            
                            <div class="relative">
                                @if(auth()->user()->isAdmin())
                                    <select name="assigned_to" class="form-input-pill appearance-none text-slate-400 bg-slate-50/50 border-slate-100 pr-10">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                        <div class="w-4 h-4 rounded-full border border-slate-300"></div>
                                    </div>
                                @else
                                    <div class="form-input-pill text-slate-500 bg-slate-100 flex items-center justify-between border-slate-200">
                                        <span>{{ $task->assignedUser->name }}</span>
                                        <div class="w-4 h-4 rounded-full border border-slate-300"></div>
                                    </div>
                                    <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-12 flex justify-center">
                        <button type="submit" class="bg-[#3b82f6] hover:bg-blue-600 transition-all text-white px-10 py-3.5 rounded-full font-bold text-base shadow-xl shadow-blue-500/30 active:scale-95">
                            Save Changes
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>

        <!-- Sidebar (Reused from Index) -->
        <div class="w-full lg:w-[320px] flex flex-col gap-6 shrink-0 xl:w-[340px]">
            <div class="bg-white rounded-[16px] text-gray-800 shadow-xl overflow-hidden pt-6">
                <!-- User Profile -->
                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="w-14 h-14 rounded-full bg-[#e2e8f0] flex items-center justify-center text-gray-700 overflow-hidden mb-3 border-4 border-white shadow-sm">
                       <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-3.333 0-10 1.667-10 5v3h20v-3c0-3.333-6.667-5-10-5z"/></svg>
                    </div>
                    <div class="text-center">
                        <h2 class="font-bold text-lg text-gray-900 leading-tight">{{ auth()->user()->name }}</h2>
                    </div>
                </div>

                <!-- Navigation List -->
                <div class="flex flex-col w-full text-[15px] font-medium border-t border-gray-100">
                    <div class="border-b border-gray-100">
                        <button onclick="document.getElementById('task-sub').classList.toggle('hidden')" class="flex justify-between items-center w-full py-3.5 px-6 text-gray-700 hover:bg-gray-50 group">
                            Tasks
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="task-sub" class="bg-[#3b82f6] text-white">
                             <a href="{{ route('tasks.index') }}" class="py-3.5 px-6 block">Tasks</a>
                        </div>
                    </div>
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

                <!-- Circular Stats (Simplified SVG from index) -->
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex flex-col items-center">
                            <div class="relative w-14 h-14 mb-2">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-[#3b82f6]" stroke-width="4" stroke-linecap="round" stroke-dasharray="100, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800">150</div>
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest text-center">Trust Tasks</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="relative w-14 h-14 mb-2">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-blue-200" stroke-width="4" stroke-linecap="round" stroke-dasharray="60, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800">90</div>
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest text-center">Completed</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="relative w-14 h-14 mb-2">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-blue-300" stroke-width="4" stroke-linecap="round" stroke-dasharray="80, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800">90</div>
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest text-center">In Progress</span>
                        </div>
                    </div>
                    <p class="text-xs text-center font-bold text-gray-900 mb-4 tracking-wide uppercase">Monthly Task Completion</p>
                    <div class="flex justify-between items-end h-10 px-4">
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[30%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[60%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[80%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[100%]"></div>
                        <div class="w-3 bg-[#3b82f6] rounded-t-sm h-[20%]"></div>
                    </div>
                </div>
            </div>

            <div class="bg-[#242c3d] rounded-[16px] p-6 text-white shadow-xl flex flex-col items-center">
                <h3 class="text-sm font-bold tracking-wide self-start mb-6">Monthly Task Completion</h3>
                <div class="w-full h-32 flex items-end justify-between px-2 pb-2">
                    <div class="w-4 bg-[#3b82f6] rounded-t-sm h-[40%]"></div>
                    <div class="w-4 bg-[#3b82f6] rounded-t-sm h-[80%]"></div>
                    <div class="w-4 bg-[#3b82f6] rounded-t-sm h-[10%]"></div>
                    <div class="w-4 bg-[#3b82f6] rounded-t-sm h-[90%]"></div>
                    <div class="w-4 bg-[#3b82f6] rounded-t-sm h-[50%]"></div>
                    <div class="w-4 bg-[#3b82f6] rounded-t-sm h-[70%]"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setPriority(val) {
        document.getElementById('priority-input').value = val;
        
        // Update visual pills (simplified for demo/manual selection)
        document.querySelectorAll('.priority-pill').forEach(btn => {
            if(btn.dataset.value === val) {
                btn.classList.add('bg-blue-500', 'text-white');
                btn.classList.remove('bg-slate-100', 'text-slate-500');
            } else {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-slate-100', 'text-slate-500');
            }
        });
    }
</script>

</body>
</html>
