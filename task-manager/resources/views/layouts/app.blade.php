<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Setup Chart.js for dashboard -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col sm:flex-row">
            
            <!-- Sidebar Navigation -->
            <nav class="w-full sm:w-64 bg-slate-900 text-white flex flex-col border-r border-slate-800 shrink-0 hidden sm:flex">
                <div class="p-6">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        Admin<span class="text-blue-500">Pro</span>
                    </a>
                </div>

                <div class="px-4 py-2 flex-grow space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('tasks.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Tasks Manager
                    </a>

                    @if(auth()->user() && auth()->user()->isAdmin())
                        <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Administration</div>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-not-allowed border border-slate-800/50" title="Coming soon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            User Directory
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-not-allowed border border-slate-800/50" title="Coming soon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 001.815 1.292c1.78-.31 3.1 1.02 2.79 2.79a1.724 1.724 0 001.292 1.815c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.292 1.815c.31 1.78-1.02 3.1-2.79 2.79a1.724 1.724 0 00-1.815 1.292c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-1.815-1.292c-1.78.31-3.1-1.02-2.79-2.79a1.724 1.724 0 00-1.292-1.815c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.292-1.815c-.31-1.78 1.02-3.1 2.79-2.79a1.724 1.724 0 001.815-1.292z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Settings
                        </a>
                    @endif
                </div>
                
                <div class="p-4 border-t border-slate-800">
                    <div class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shrink-0 shadow-inner">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 transition-colors" title="Log Out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto w-full">
                <!-- Mobile header -->
                <div class="sm:hidden bg-slate-900 text-white p-4 flex justify-between items-center shadow-md">
                    <div class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        Admin<span class="text-blue-500">Pro</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm border border-slate-700 px-3 py-1 rounded">Logout</button>
                    </form>
                </div>
                
                <!-- Navbar mobile (simple nav links) -->
                <div class="sm:hidden flex bg-slate-800 text-slate-300 px-2 py-3 gap-2 overflow-x-auto">
                    <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-md text-sm whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'bg-slate-700' }}">Dashboard</a>
                    <a href="{{ route('tasks.index') }}" class="px-3 py-1.5 rounded-md text-sm whitespace-nowrap {{ request()->routeIs('tasks.*') ? 'bg-blue-600 text-white' : 'bg-slate-700' }}">Tasks</a>
                </div>

                <div class="p-6 sm:p-8 flex-1 w-full max-w-7xl mx-auto">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50/50 backdrop-blur-sm border border-green-200 text-green-800 shadow-sm flex items-start gap-3 transform transition-all animate-fade-in">
                            <svg class="w-5 h-5 mt-0.5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="font-medium">Success</h3>
                                <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    {{ $slot }}
                </div>
            </main>
            
        </div>
        
        <script>
            // Basic animation for flash messages
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    let flashes = document.querySelectorAll('.animate-fade-in');
                    flashes.forEach(function(flash) {
                        flash.style.opacity = '0';
                        flash.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => flash.remove(), 500);
                    });
                }, 5000);
            });
        </script>
    </body>
</html>
