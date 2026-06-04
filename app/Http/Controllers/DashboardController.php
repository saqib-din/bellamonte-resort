<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\Event;
use App\Models\User;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // public function show()
    // {
    //     return view('dashboard');
    // }

    public function index()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // ══ ROOMS 
        $totalRooms       = Room::count();
        $availableRooms   = Room::where('status', 'Available')->count();
        $occupiedRooms    = Room::where('status', 'Occupied')->count();
        $maintenanceRooms = Room::where('status', 'Maintenance')->count();
        $occupancyRate    = $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100)
            : 0;

        // ══ BOOKINGS 
        $totalBookings      = Booking::count();
        $todayCheckins      = Booking::whereDate('check_in', $today)->count();
        $todayCheckouts     = Booking::whereDate('check_out', $today)->count();
        $activeBookings     = Booking::where('status', 'Checked In')->count();
        $confirmedBookings  = Booking::where('status', 'Confirmed')->count();
        $thisMonthBookings  = Booking::where('created_at', '>=', $thisMonth)->count();
        $lastMonthBookings  = Booking::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $bookingGrowth      = $lastMonthBookings > 0
            ? round((($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100)
            : 100;

        // ══ REVENUE
        $revenueToday      = Bill::where('status', 'Paid')->whereDate('issue_date', $today)->sum('total_amount');
        $revenueThisMonth  = Bill::where('status', 'Paid')->where('issue_date', '>=', $thisMonth)->sum('total_amount');
        $revenueLastMonth  = Bill::where('status', 'Paid')->whereBetween('issue_date', [$lastMonth, $lastMonthEnd])->sum('total_amount');
        $revenueTotal      = Bill::where('status', 'Paid')->sum('total_amount');
        $pendingAmount     = Bill::whereIn('status', ['Unpaid', 'Partial'])->sum('balance_due');
        $revenueGrowth     = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : 100;

        // ══ CUSTOMERS 
        $totalCustomers   = Customer::count();
        $newCustomers     = Customer::where('created_at', '>=', $thisMonth)->count();
        $activeCustomers  = Customer::where('status', 'Active')->count();

        // ══ BILLS / INVOICES 
        $totalBills    = Bill::count();
        $paidBills     = Bill::where('status', 'Paid')->count();
        $unpaidBills   = Bill::where('status', 'Unpaid')->count();
        $partialBills  = Bill::where('status', 'Partial')->count();

        // ══ USERS 
        $totalUsers    = User::count();
        $activeUsers   = User::where('status', 'active')->count();



        // ══ RECENT DATA 
        $recentBookings = Booking::with(['room', 'customer'])
            ->latest()->take(6)->get();

        $recentBills = Bill::with('customer')
            ->latest()->take(5)->get();

        $todayCheckinsList = Booking::with(['room', 'customer'])
            ->whereDate('check_in', $today)
            ->get();

        $todayCheckoutsList = Booking::with(['room', 'customer'])
            ->where('status', 'Checked In')
            ->whereDate('check_out', $today)
            ->get();

        $upcomingCheckouts = Booking::with(['room', 'customer'])
            ->where('status', 'Checked In')
            ->whereBetween('check_out', [today()->addDay(), today()->addDays(3)])
            ->orderBy('check_out')
            ->get();

        $rooms = Room::all();

        $totalContacts = Contact::count();
        $repliedContacts = Contact::where('is_replied', true)->count();
        $unrepliedContacts = Contact::where('is_replied', false)->count();

        $totalEvents = Event::count();
        $activeEvents = Event::where('is_active', true)->count();
        $upcomingEvents = Event::where('event_date', '>=', now())->count();
        $pastEvents = Event::where('event_date', '<', now())->count();


        return view('dashboard', compact(
            'rooms',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'occupancyRate',

            'totalEvents',
            'activeEvents',
            'upcomingEvents',
            'pastEvents',

            'totalBookings',
            'todayCheckins',
            'todayCheckouts',
            'activeBookings',
            'confirmedBookings',
            'thisMonthBookings',
            'bookingGrowth',

            'revenueToday',
            'revenueThisMonth',
            'revenueLastMonth',
            'revenueTotal',
            'pendingAmount',
            'revenueGrowth',

            'totalCustomers',
            'newCustomers',
            'activeCustomers',

            'totalContacts',
            'repliedContacts',
            'unrepliedContacts',

            'totalBills',
            'paidBills',
            'unpaidBills',
            'partialBills',

            'totalUsers',
            'activeUsers',

            'recentBookings',
            'recentBills',
            'todayCheckinsList',
            'todayCheckoutsList',
            'upcomingCheckouts'
        ));
    }
}
