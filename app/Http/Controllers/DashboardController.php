<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\Event;
use App\Models\User;
use App\Models\Contact;
use App\Models\FoodOrder;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $today        = Carbon::today();
        $thisMonth    = Carbon::now()->startOfMonth();
        $lastMonth    = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // ══ ROOMS
        $totalRooms     = Room::count();
        $availableRooms = Room::where('status', 'Available')->count();

        // ══ BOOKINGS
        $totalBookings     = Booking::count();
        $thisMonthBookings = Booking::where('created_at', '>=', $thisMonth)->count();
        $lastMonthBookings = Booking::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $bookingGrowth     = $lastMonthBookings > 0
            ? round((($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100)
            : 100;

        // ══ REVENUE (BILLS)
        $revenueToday     = Bill::where('status', 'Paid')->whereDate('issue_date', $today)->sum('total_amount');
        $revenueThisMonth = Bill::where('status', 'Paid')->where('issue_date', '>=', $thisMonth)->sum('total_amount');
        $revenueLastMonth = Bill::where('status', 'Paid')->whereBetween('issue_date', [$lastMonth, $lastMonthEnd])->sum('total_amount');
        $pendingAmount    = Bill::whereIn('status', ['Unpaid', 'Partial'])->sum('balance_due');
        $unpaidBills      = Bill::where('status', 'Unpaid')->count();
        $partialBills     = Bill::where('status', 'Partial')->count();
        $revenueGrowth    = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : 100;

        // ══ FOOD ORDERS
        $totalFoodOrders      = FoodOrder::count();
        $pendingFoodOrders    = FoodOrder::where('status', 'Pending')->count();
        $servedFoodOrders     = FoodOrder::where('status', 'Served')->count();
        $foodRevenueToday     = FoodOrder::where('payment_status', 'Paid')->whereDate('created_at', $today)->sum('total_amount');
        $foodRevenueThisMonth = FoodOrder::where('payment_status', 'Paid')->where('created_at', '>=', $thisMonth)->sum('total_amount');
        $foodPendingAmount    = FoodOrder::whereIn('payment_status', ['Unpaid', 'Partial'])->sum('balance_due');

        // ══ CUSTOMERS
        $totalCustomers = Customer::count();
        $newCustomers   = Customer::where('created_at', '>=', $thisMonth)->count();

        // ══ EVENTS
        $totalEvents  = Event::count();
        $activeEvents = Event::where('is_active', true)->count();

        // ══ CONTACTS
        $totalContacts   = Contact::count();
        $repliedContacts = Contact::where('is_replied', true)->count();

        // ══ USERS
        $totalUsers  = User::count();
        $activeUsers = User::where('status', 'active')->count();

        return Inertia::render('Dashboard', [
            'today' => $today->format('l, F j, Y'),
            'stats' => [
                'totalRooms'           => $totalRooms,
                'availableRooms'       => $availableRooms,
                'totalBookings'        => $totalBookings,
                'thisMonthBookings'    => $thisMonthBookings,
                'bookingGrowth'        => $bookingGrowth,
                'totalCustomers'       => $totalCustomers,
                'newCustomers'         => $newCustomers,
                'revenueToday'         => $revenueToday,
                'revenueThisMonth'     => $revenueThisMonth,
                'revenueGrowth'        => $revenueGrowth,
                'pendingAmount'        => $pendingAmount,
                'unpaidBills'          => $unpaidBills,
                'partialBills'         => $partialBills,
                'totalFoodOrders'      => $totalFoodOrders,
                'pendingFoodOrders'    => $pendingFoodOrders,
                'servedFoodOrders'     => $servedFoodOrders,
                'foodRevenueToday'     => $foodRevenueToday,
                'foodRevenueThisMonth' => $foodRevenueThisMonth,
                'foodPendingAmount'    => $foodPendingAmount,
                'totalEvents'          => $totalEvents,
                'activeEvents'         => $activeEvents,
                'totalContacts'        => $totalContacts,
                'repliedContacts'      => $repliedContacts,
                'totalUsers'           => $totalUsers,
                'activeUsers'          => $activeUsers,
            ],
        ]);
    }
}
