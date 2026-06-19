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
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats are the same for everyone and heavy at scale, so cache them
        // for 5 minutes. Queries below are written to use indexes.
        $stats = Cache::remember('dashboard_stats', 300, function () {
            $today        = Carbon::today();
            $todayEnd     = Carbon::today()->endOfDay();
            $thisMonth    = Carbon::now()->startOfMonth();
            $lastMonth    = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            // Rooms
            $totalRooms     = Room::count();
            $availableRooms = Room::where('status', 'Available')->count();

            // Bookings
            $totalBookings     = Booking::count();
            $thisMonthBookings = Booking::where('created_at', '>=', $thisMonth)->count();
            $lastMonthBookings = Booking::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
            $bookingGrowth     = $lastMonthBookings > 0
                ? round((($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100)
                : 100;

            // Revenue (Bills) - issue_date is a DATE column, exact/range match uses the index
            $revenueToday     = Bill::where('status', 'Paid')->where('issue_date', $today->toDateString())->sum('total_amount');
            $revenueThisMonth = Bill::where('status', 'Paid')->where('issue_date', '>=', $thisMonth->toDateString())->sum('total_amount');
            $revenueLastMonth = Bill::where('status', 'Paid')->whereBetween('issue_date', [$lastMonth->toDateString(), $lastMonthEnd->toDateString()])->sum('total_amount');
            $pendingAmount    = Bill::whereIn('status', ['Unpaid', 'Partial'])->sum('balance_due');
            $unpaidBills      = Bill::where('status', 'Unpaid')->count();
            $partialBills     = Bill::where('status', 'Partial')->count();
            $revenueGrowth    = $revenueLastMonth > 0
                ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
                : 100;

            // Food orders - created_at is datetime, range match uses the index
            $totalFoodOrders      = FoodOrder::count();
            $pendingFoodOrders    = FoodOrder::where('status', 'Pending')->count();
            $servedFoodOrders     = FoodOrder::where('status', 'Served')->count();
            $foodRevenueToday     = FoodOrder::where('payment_status', 'Paid')->whereBetween('created_at', [$today, $todayEnd])->sum('total_amount');
            $foodRevenueThisMonth = FoodOrder::where('payment_status', 'Paid')->where('created_at', '>=', $thisMonth)->sum('total_amount');
            $foodPendingAmount    = FoodOrder::whereIn('payment_status', ['Unpaid', 'Partial'])->sum('balance_due');

            // Customers
            $totalCustomers = Customer::count();
            $newCustomers   = Customer::where('created_at', '>=', $thisMonth)->count();

            // Events
            $totalEvents  = Event::count();
            $activeEvents = Event::where('is_active', true)->count();

            // Contacts
            $totalContacts   = Contact::count();
            $repliedContacts = Contact::where('is_replied', true)->count();

            // Users
            $totalUsers  = User::count();
            $activeUsers = User::where('status', 'active')->count();

            return [
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
            ];
        });

        return Inertia::render('Dashboard', [
            'today' => Carbon::now()->format('l, F j, Y'),
            'stats' => $stats,
        ]);
    }
}
