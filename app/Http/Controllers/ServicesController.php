<?php

namespace App\Http\Controllers;

use App\Models\MainModule;
use App\Models\Booking;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        // 3 main modules with count of bookings / items
        $modules = MainModule::with(['subModules.serviceItems'])->get();

        // Query bookings with search and filter
        $query = Booking::with(['customer', 'bookingItems.serviceItem.subModule.mainModule'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $bookings = $query->paginate(5)->withQueryString();

        return view('services.index', compact('modules', 'bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'bookingItems.serviceItem.subModule.mainModule']);
        return response()->json([
            'success' => true,
            'booking' => $booking
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->back()->with('success', 'Booking record deleted successfully!');
    }
}
