<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Ticket;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Fetch total, open, closed tickets as before
        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('Open');
        })->count();
        $closedTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('Closed');
        })->count();

        // Group tickets by month of creation
        $ticketsByMonth = Ticket::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Group tickets by day of creation
        $ticketsByDay = Ticket::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, COUNT(*) as count')
            ->groupBy('year', 'month', 'day')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->orderBy('day', 'asc')
            ->get();

        // Fetch categories and count tickets per category
        $categories = Category::all();
        $ticketCountsByCategory = [];
        foreach ($categories as $category) {
            $ticketCountsByCategory[$category->name] = Ticket::where('category_id', $category->id)->count();
        }

        return view('home', compact('totalTickets', 'openTickets', 'closedTickets', 'ticketsByMonth', 'ticketsByDay', 'ticketCountsByCategory'));
    }
}
