<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        // For admin dashboard, we show all tasks stats
        // If a user gets here (should be blocked by middleware), we would scope it.
        // For security, assuming middleware protects it.

        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $highPriorityTasks = Task::where('priority', 'high')->where('status', '!=', 'completed')->count();

        // Data for Chart.js - tasks grouped by status
        $statusCounts = Task::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();

        // Recent tasks for table
        $recentTasks = Task::with('assignedUser')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'highPriorityTasks',
            'statusCounts',
            'recentTasks'
        ));
    }
}
