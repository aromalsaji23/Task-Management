<x-app-layout>
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
            <a href="{{ route('tasks.index') }}" class="hover:text-blue-600 transition-colors pointer-events-auto">Tasks</a>
            <span>/</span>
            <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600 transition-colors pointer-events-auto">{{ Str::limit($task->title, 20) }}</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Edit</span>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            Edit Task
        </h1>
        <p class="text-slate-500 mt-1">Update task details, status, or assignment.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="p-6 sm:p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Task Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3 {{ $errors->has('title') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '' }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="5" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3 {{ $errors->has('description') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '' }}">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-semibold text-slate-700 mb-1">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" id="priority" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3 text-slate-700">
                        @foreach(\App\Enums\TaskPriorityEnum::cases() as $priority)
                            <option value="{{ $priority->value }}" {{ old('priority', $task->priority->value) === $priority->value ? 'selected' : '' }}>
                                {{ $priority->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3 text-slate-700">
                        @foreach(\App\Enums\TaskStatusEnum::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status', $task->status->value) === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-semibold text-slate-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3 text-slate-700">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-semibold text-slate-700 mb-1">Assign User <span class="text-red-500">*</span></label>
                    @if(auth()->user()->isAdmin())
                        <select name="assigned_to" id="assigned_to" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3 text-slate-700">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        <!-- Non-admin can't reassign tasks -->
                        <select name="assigned_to" id="assigned_to" disabled class="block w-full rounded-lg border-slate-300 bg-slate-100 text-slate-500 shadow-sm sm:text-sm py-2 px-3 cursor-not-allowed">
                            <option value="{{ $task->assigned_to }}" selected>
                                {{ $task->assignedUser->name }} ({{ $task->assignedUser->email }})
                            </option>
                        </select>
                        <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">
                        <p class="text-xs text-slate-500 mt-1">Only admins can reassign tasks.</p>
                    @endif
                    @error('assigned_to')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-slate-200 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('tasks.show', $task) }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 text-center pointer-events-auto">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 text-center pointer-events-auto cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
