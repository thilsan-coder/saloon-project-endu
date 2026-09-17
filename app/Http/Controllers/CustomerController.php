<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('bookings')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(5)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create($request->only('name', 'phone', 'email', 'address'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'customer' => $customer,
            ]);
        }

        return redirect()->back()->with('success', 'Customer added successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load(['bookings.bookingItems.serviceItem.subModule.mainModule']);

        return response()->json([
            'success' => true,
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $customer->update($request->only('name', 'phone', 'email', 'address'));

        return redirect()->back()->with('success', 'Customer record updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->back()->with('success', 'Customer profile deleted successfully!');
    }
}
