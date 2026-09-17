<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\ServiceItem;
use App\Models\MainModule;
use App\Models\BookingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalCustomers = Customer::count();
        $totalLeads = Booking::where('type', 'lead')->count();
        $totalQuotations = Booking::where('type', 'quotation')->count();

        // Recent Bookings (5 latest)
        $recentBookings = Booking::with('customer', 'bookingItems.serviceItem.subModule.mainModule')
            ->latest()
            ->take(5)
            ->get();

        // 1. Chart: Bookings Over Time (Last 6 Months or Last 30 Days)
        $bookingsOverTime = Booking::select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month_year'),
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as sort_key'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month_year', 'sort_key')
            ->orderBy('sort_key', 'asc')
            ->take(6)
            ->get();

        $lineChartLabels = $bookingsOverTime->pluck('month_year')->toArray();
        $lineChartData = $bookingsOverTime->pluck('count')->toArray();

        // 2. Chart: Revenue by Main Service Module
        $modules = MainModule::with('subModules.serviceItems')->get();
        $revenueByModuleLabels = [];
        $revenueByModuleData = [];

        foreach ($modules as $module) {
            $revenueByModuleLabels[] = $module->name;
            $serviceItemIds = $module->subModules->flatMap->serviceItems->pluck('id');
            
            $moduleRevenue = BookingItem::whereIn('service_item_id', $serviceItemIds)
                ->whereHas('booking', function ($q) {
                    $q->where('status', '!=', 'cancelled');
                })
                ->sum('price_at_booking');

            $revenueByModuleData[] = round($moduleRevenue, 2);
        }

        // 3. Chart: Most Popular Services (Top 5 items by booking count)
        $popularServices = BookingItem::select('service_item_id', DB::raw('COUNT(*) as total_count'))
            ->groupBy('service_item_id')
            ->orderBy('total_count', 'desc')
            ->take(5)
            ->with('serviceItem')
            ->get();

        $pieChartLabels = [];
        $pieChartData = [];

        foreach ($popularServices as $item) {
            if ($item->serviceItem) {
                $pieChartLabels[] = $item->serviceItem->name;
                $pieChartData[] = $item->total_count;
            }
        }

        // Fallback if empty
        if (empty($lineChartLabels)) {
            $lineChartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            $lineChartData = [0, 0, 0, 0, 0, 0];
        }

        return view('dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'totalCustomers',
            'totalLeads',
            'totalQuotations',
            'recentBookings',
            'lineChartLabels',
            'lineChartData',
            'revenueByModuleLabels',
            'revenueByModuleData',
            'pieChartLabels',
            'pieChartData'
        ));
    }
}
