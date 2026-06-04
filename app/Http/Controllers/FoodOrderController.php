<?php

namespace App\Http\Controllers;

use App\Models\FoodOrder;
use App\Models\FoodOrderItem;
use App\Models\FoodCategory;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class FoodOrderController extends Controller
{
    public function index()
    {
        $orders = FoodOrder::with(['items', 'customer'])->latest()->get();
        return view('pages.admin-side.food.orders.index', compact('orders'));
    }

    public function create()
    {
        $categories = FoodCategory::with('availableItems')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $bookings   = Booking::with('room')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->latest()->get();
        $customers  = Customer::orderBy('name')->get();

        return view('pages.admin-side.food.orders.create', compact('categories', 'bookings', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'     => 'required|string|max:255',
            'order_type'     => 'required|string',
            'payment_method' => 'required|string',
            'items'          => 'required|array|min:1',
            'items.*.food_item_id' => 'required|exists:food_items,id',
            'items.*.quantity'     => 'required|integer|min:1',
        ]);

        // Calculate totals
        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $foodItem  = \App\Models\FoodItem::findOrFail($item['food_item_id']);
            $lineTotal = $foodItem->price * $item['quantity'];
            $subtotal += $lineTotal;
            $orderItems[] = [
                'food_item_id' => $foodItem->id,
                'item_name'    => $foodItem->name,
                'unit_price'   => $foodItem->price,
                'quantity'     => $item['quantity'],
                'subtotal'     => $lineTotal,
            ];
        }

        $discount   = (float) $request->discount ?? 0;
        $taxPct     = (float) $request->tax_percent ?? 0;
        $afterDis   = $subtotal - $discount;
        $taxAmount  = round($afterDis * ($taxPct / 100), 2);
        $total      = round($afterDis + $taxAmount, 2);
        $paid       = (float) $request->amount_paid ?? 0;
        $balance    = max(0, $total - $paid);

        $order = FoodOrder::create([
            'order_number'   => FoodOrder::generateOrderNumber(),
            'booking_id'     => $request->booking_id ?: null,
            'customer_id'    => $request->customer_id ?: null,
            'guest_name'     => $request->guest_name,
            'guest_phone'    => $request->guest_phone,
            'room_number'    => $request->room_number,
            'order_type'     => $request->order_type,
            'payment_method' => $request->payment_method,
            'status'         => 'Pending',
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax_percent'    => $taxPct,
            'tax_amount'     => $taxAmount,
            'total_amount'   => $total,
            'amount_paid'    => $paid,
            'balance_due'    => $balance,
            'notes'          => $request->notes,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('food.orders.show', $order)
            ->with('success', 'Food order placed successfully!');
    }

    public function show(FoodOrder $foodOrder)
    {
        $foodOrder->load(['items.foodItem', 'booking', 'customer']);
        return view('pages.admin-side.food.orders.show', compact('foodOrder'));
    }

    public function edit(FoodOrder $foodOrder)
    {
        $categories = FoodCategory::with('availableItems')
            ->where('is_active', true)
            ->orderBy('sort_order')->get();
        $bookings   = Booking::with('room')
            ->whereIn('status', ['confirmed', 'checked_in'])->latest()->get();
        $customers  = Customer::orderBy('name')->get();

        $existingItems = $foodOrder->items->map(function ($i) {
            return [
                'id'    => $i->food_item_id,
                'name'  => $i->item_name,
                'price' => (float) $i->unit_price,
                'qty'   => $i->quantity,
            ];
        })->values();

        return view('pages.admin-side.food.orders.edit', compact(
            'foodOrder',
            'categories',
            'bookings',
            'customers',
            'existingItems'
        ));
    }

    public function update(Request $request, FoodOrder $foodOrder)
    {
        $request->validate([
            'guest_name'     => 'required|string|max:255',
            'order_type'     => 'required|string',
            'payment_method' => 'required|string',
            'items'          => 'required|array|min:1',
        ]);

        $subtotal   = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $foodItem  = \App\Models\FoodItem::findOrFail($item['food_item_id']);
            $lineTotal = $foodItem->price * $item['quantity'];
            $subtotal += $lineTotal;
            $orderItems[] = [
                'food_item_id' => $foodItem->id,
                'item_name'    => $foodItem->name,
                'unit_price'   => $foodItem->price,
                'quantity'     => $item['quantity'],
                'subtotal'     => $lineTotal,
            ];
        }

        $discount  = (float) $request->discount ?? 0;
        $taxPct    = (float) $request->tax_percent ?? 0;
        $afterDis  = $subtotal - $discount;
        $taxAmount = round($afterDis * ($taxPct / 100), 2);
        $total     = round($afterDis + $taxAmount, 2);
        $paid      = (float) $request->amount_paid ?? 0;
        $balance   = max(0, $total - $paid);

        $foodOrder->update([
            'booking_id'     => $request->booking_id ?: null,
            'customer_id'    => $request->customer_id ?: null,
            'guest_name'     => $request->guest_name,
            'guest_phone'    => $request->guest_phone,
            'room_number'    => $request->room_number,
            'order_type'     => $request->order_type,
            'payment_method' => $request->payment_method,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax_percent'    => $taxPct,
            'tax_amount'     => $taxAmount,
            'total_amount'   => $total,
            'amount_paid'    => $paid,
            'balance_due'    => $balance,
            'notes'          => $request->notes,
        ]);

        // Re-create items
        $foodOrder->items()->delete();
        foreach ($orderItems as $item) {
            $foodOrder->items()->create($item);
        }

        return redirect()->route('food.orders.show', $foodOrder)
            ->with('success', 'Order updated successfully!');
    }

    public function destroy(FoodOrder $foodOrder)
    {
        $foodOrder->items()->delete();
        $foodOrder->delete();
        return redirect()->route('food.orders.index')->with('success', 'Order deleted.');
    }

    public function updateStatus(Request $request, FoodOrder $foodOrder)
    {
        $request->validate(['status' => 'required|in:Pending,Preparing,Served,Paid,Cancelled']);
        $foodOrder->update(['status' => $request->status]);
        return back()->with('success', 'Status updated to ' . $request->status);
    }

    public function print(FoodOrder $foodOrder)
    {
        $foodOrder->load(['items.foodItem', 'booking']);
        return view('pages.admin-side.food.orders.print', compact('foodOrder'));
    }
}
