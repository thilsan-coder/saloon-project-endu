<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MainModule;
use App\Models\ServiceItem;
use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function create()
    {
        $customers = Customer::orderBy('name', 'asc')->get();
        $modules = MainModule::with(['subModules.serviceItems'])->get();

        return view('booking.wizard', compact('customers', 'modules'));
    }

    public function storeLead(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*' => 'exists:service_items,id',
        ]);

        $items = ServiceItem::whereIn('id', $request->items)->get();
        $totalAmount = $items->sum('price');

        $booking = Booking::create([
            'booking_number' => 'SB-LD-' . strtoupper(Str::random(6)),
            'customer_id' => $request->customer_id,
            'type' => 'lead',
            'status' => 'pending',
            'total_amount' => $totalAmount,
        ]);

        foreach ($items as $item) {
            BookingItem::create([
                'booking_id' => $booking->id,
                'service_item_id' => $item->id,
                'price_at_booking' => $item->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'redirect_url' => route('services.index'),
        ]);
    }

    public function storeQuotation(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*' => 'exists:service_items,id',
            'signature' => 'required|string', // base64 string
        ]);

        $items = ServiceItem::whereIn('id', $request->items)->get();
        $totalAmount = $items->sum('price');

        // Process Base64 Signature Image
        $signatureData = $request->signature;
        $signaturePath = null;

        if (Str::startsWith($signatureData, 'data:image')) {
            $imageParts = explode(';base64,', $signatureData);
            $imageTypeAux = explode('image/', $imageParts[0]);
            $imageType = $imageTypeAux[1] ?? 'png';
            $imageBase64 = base64_decode($imageParts[1]);
            
            $fileName = 'signatures/sig_' . time() . '_' . Str::random(8) . '.' . $imageType;
            Storage::disk('public')->put($fileName, $imageBase64);
            $signaturePath = 'storage/' . $fileName;
        } else {
            $signaturePath = $signatureData;
        }

        $booking = Booking::create([
            'booking_number' => 'SB-QT-' . strtoupper(Str::random(6)),
            'customer_id' => $request->customer_id,
            'type' => 'quotation',
            'status' => 'confirmed',
            'total_amount' => $totalAmount,
            'signature_path' => $signaturePath,
        ]);

        foreach ($items as $item) {
            BookingItem::create([
                'booking_id' => $booking->id,
                'service_item_id' => $item->id,
                'price_at_booking' => $item->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Quotation generated successfully!',
            'booking_id' => $booking->id,
            'pdf_url' => route('booking.pdf', $booking->id),
            'redirect_url' => route('services.index'),
        ]);
    }

    public function downloadPdf($id)
    {
        $booking = Booking::with([
            'customer',
            'bookingItems.serviceItem.subModule.mainModule'
        ])->findOrFail($id);

        // Group items by main module and sub-module
        $groupedItems = [];
        foreach ($booking->bookingItems as $bItem) {
            $item = $bItem->serviceItem;
            $mainModuleName = $item->subModule->mainModule->name ?? 'General Services';
            $subModuleName = $item->subModule->name ?? 'Standard Items';

            $groupedItems[$mainModuleName][$subModuleName][] = [
                'name' => $item->name,
                'price' => $bItem->price_at_booking,
            ];
        }

        // Check signature data / fallback
        $signatureBase64 = null;
        if ($booking->signature_path && file_exists(public_path($booking->signature_path))) {
            $type = pathinfo(public_path($booking->signature_path), PATHINFO_EXTENSION);
            $data = file_get_contents(public_path($booking->signature_path));
            $signatureBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } elseif ($booking->signature_path && Str::startsWith($booking->signature_path, 'data:image')) {
            $signatureBase64 = $booking->signature_path;
        }

        $pdf = Pdf::loadView('pdf.quotation', [
            'booking' => $booking,
            'groupedItems' => $groupedItems,
            'signatureBase64' => $signatureBase64,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Salon_Quotation_' . $booking->booking_number . '.pdf');
    }
}
