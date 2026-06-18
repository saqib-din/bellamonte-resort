<?php

namespace App\Http\Controllers;

use App\Models\FoodOrder;
use App\Models\FoodItem;
use App\Models\FoodCategory;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FoodOrderController extends Controller
{
    private const SORTABLE = ['order_number', 'guest_name', 'total_amount', 'status', 'created_at'];

    public function index(Request $request)
    {
        $query = FoodOrder::with(['items', 'customer']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                    ->orWhere('guest_name', 'like', "%{$s}%")
                    ->orWhere('guest_phone', 'like', "%{$s}%");
            });
        }

        $sort = in_array($request->sort, self::SORTABLE, true) ? $request->sort : 'created_at';
        $dir  = $request->dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        $perPage = (int) $request->input('per_page', 15);

        $orders = $query->paginate($perPage)->withQueryString()->through(fn ($o) => [
            'uuid'            => $o->uuid,
            'order_number'    => $o->order_number,
            'date'            => optional($o->created_at)->format('d M Y, h:i A'),
            'guest_name'      => $o->guest_name,
            'guest_phone'     => $o->guest_phone,
            'room_number'     => $o->room_number,
            'order_type'      => $o->order_type,
            'orderTypeBadge'  => $o->order_type_badge_class,
            'items_count'     => $o->items->count(),
            'total_amount'    => $o->total_amount,
            'balance_due'     => $o->balance_due,
            'status'          => $o->status,
            'statusBadge'     => $o->status_badge_class,
        ]);

        return Inertia::render('Food/Orders/Index', [
            'orders'  => $orders,
            'filters' => [
                'search'   => $request->search,
                'sort'     => $sort,
                'dir'      => $dir,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Food/Orders/Create', [
            'categories' => $this->categoryOptions(),
            'bookings'   => $this->bookingOptions(),
            'customers'  => $this->customerOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id'           => 'nullable|exists:bookings,id',
            'customer_id'          => 'nullable|exists:customers,id',
            'guest_name'           => 'required|string|max:255',
            'father_name'          => 'nullable|string|max:255',
            'guest_phone'          => 'nullable|string|max:20',
            'room_number'          => 'nullable|string|max:20',
            'order_type'           => 'required|in:Room Service,Dine In,Takeaway',
            'payment_method'       => 'required|string|max:50',
            'discount'             => 'nullable|numeric|min:0',
            'amount_paid'          => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string|max:1000',
            'items'                => 'required|array|min:1',
            'items.*.food_item_id' => 'required|exists:food_items,id',
            'items.*.quantity'     => 'required|integer|min:1|max:1000',
        ], [
            'items.required'            => 'Please add at least one item to the order.',
            'items.min'                 => 'Please add at least one item to the order.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min'      => 'Quantity must be at least 1.',
        ]);

        [$subtotal, $orderItems, $totals] = $this->computeTotals($request);

        if ($totals['discount'] > $subtotal) {
            throw ValidationException::withMessages([
                'discount' => 'Discount cannot be more than the subtotal (₨' . number_format($subtotal) . ').',
            ]);
        }
        if ($totals['paid'] > $totals['total']) {
            throw ValidationException::withMessages([
                'amount_paid' => 'Amount paid cannot be more than the total (₨' . number_format($totals['total']) . ').',
            ]);
        }

        $order = FoodOrder::create([
            'order_number'   => FoodOrder::generateOrderNumber(),
            'booking_id'     => $request->booking_id ?: null,
            'customer_id'    => $request->customer_id ?: null,
            'guest_name'     => $request->guest_name,
            'father_name'    => $request->father_name,
            'guest_phone'    => $request->guest_phone,
            'room_number'    => $request->room_number,
            'order_type'     => $request->order_type,
            'payment_method' => $request->payment_method,
            'status'         => 'Pending',
            'subtotal'       => $subtotal,
            'discount'       => $totals['discount'],
            'tax_percent'    => $totals['taxPct'],
            'tax_amount'     => $totals['taxAmount'],
            'total_amount'   => $totals['total'],
            'amount_paid'    => $totals['paid'],
            'balance_due'    => $totals['balance'],
            'notes'          => $request->notes,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('food.orders.show', $order)->with('success', 'Food order placed successfully!');
    }

    public function show(FoodOrder $foodOrder)
    {
        $foodOrder->load(['items', 'booking', 'customer']);

        return Inertia::render('Food/Orders/Show', [
            'order' => $this->serializeOrder($foodOrder),
        ]);
    }

    public function edit(FoodOrder $foodOrder)
    {
        $foodOrder->load('items');

        return Inertia::render('Food/Orders/Edit', [
            'categories' => $this->categoryOptions(),
            'bookings'   => $this->bookingOptions(),
            'customers'  => $this->customerOptions(),
            'order'      => [
                'uuid'           => $foodOrder->uuid,
                'order_number'   => $foodOrder->order_number,
                'booking_id'     => $foodOrder->booking_id,
                'customer_id'    => $foodOrder->customer_id,
                'guest_name'     => $foodOrder->guest_name,
                'father_name'    => $foodOrder->father_name,
                'guest_phone'    => $foodOrder->guest_phone,
                'room_number'    => $foodOrder->room_number,
                'order_type'     => $foodOrder->order_type,
                'payment_method' => $foodOrder->payment_method,
                'discount'       => $foodOrder->discount,
                'tax_percent'    => $foodOrder->tax_percent,
                'amount_paid'    => $foodOrder->amount_paid,
                'notes'          => $foodOrder->notes,
                'cart'           => $foodOrder->items->map(fn ($i) => [
                    'id'    => $i->food_item_id,
                    'name'  => $i->item_name,
                    'price' => (float) $i->unit_price,
                    'qty'   => $i->quantity,
                ])->values(),
            ],
        ]);
    }

    public function update(Request $request, FoodOrder $foodOrder)
    {
        $request->validate([
            'booking_id'           => 'nullable|exists:bookings,id',
            'customer_id'          => 'nullable|exists:customers,id',
            'guest_name'           => 'required|string|max:255',
            'father_name'          => 'nullable|string|max:255',
            'guest_phone'          => 'nullable|string|max:20',
            'room_number'          => 'nullable|string|max:20',
            'order_type'           => 'required|in:Room Service,Dine In,Takeaway',
            'payment_method'       => 'required|string|max:50',
            'discount'             => 'nullable|numeric|min:0',
            'amount_paid'          => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string|max:1000',
            'items'                => 'required|array|min:1',
            'items.*.food_item_id' => 'required|exists:food_items,id',
            'items.*.quantity'     => 'required|integer|min:1|max:1000',
        ], [
            'items.required'            => 'Please add at least one item to the order.',
            'items.min'                 => 'Please add at least one item to the order.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min'      => 'Quantity must be at least 1.',
        ]);

        [$subtotal, $orderItems, $totals] = $this->computeTotals($request);

        if ($totals['discount'] > $subtotal) {
            throw ValidationException::withMessages([
                'discount' => 'Discount cannot be more than the subtotal (₨' . number_format($subtotal) . ').',
            ]);
        }
        if ($totals['paid'] > $totals['total']) {
            throw ValidationException::withMessages([
                'amount_paid' => 'Amount paid cannot be more than the total (₨' . number_format($totals['total']) . ').',
            ]);
        }

        $foodOrder->update([
            'booking_id'     => $request->booking_id ?: null,
            'customer_id'    => $request->customer_id ?: null,
            'guest_name'     => $request->guest_name,
            'father_name'    => $request->father_name,
            'guest_phone'    => $request->guest_phone,
            'room_number'    => $request->room_number,
            'order_type'     => $request->order_type,
            'payment_method' => $request->payment_method,
            'subtotal'       => $subtotal,
            'discount'       => $totals['discount'],
            'tax_percent'    => $totals['taxPct'],
            'tax_amount'     => $totals['taxAmount'],
            'total_amount'   => $totals['total'],
            'amount_paid'    => $totals['paid'],
            'balance_due'    => $totals['balance'],
            'notes'          => $request->notes,
        ]);

        $foodOrder->items()->delete();
        foreach ($orderItems as $item) {
            $foodOrder->items()->create($item);
        }

        return redirect()->route('food.orders.show', $foodOrder)->with('success', 'Order updated successfully!');
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

    // ── Helpers ──────────────────────────────────────
    private function computeTotals(Request $request): array
    {
        $subtotal   = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $foodItem  = FoodItem::findOrFail($item['food_item_id']);
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

        $discount  = (float) ($request->discount ?? 0);
        $taxPct    = 0;
        $afterDis  = $subtotal - $discount;
        $taxAmount = 0;
        $total     = round($afterDis, 2);
        $paid      = (float) ($request->amount_paid ?? 0);
        $balance   = max(0, $total - $paid);

        return [$subtotal, $orderItems, compact('discount', 'taxPct', 'taxAmount', 'total', 'paid', 'balance')];
    }

    private function serializeOrder(FoodOrder $o): array
    {
        return [
            'uuid'           => $o->uuid,
            'order_number'   => $o->order_number,
            'guest_name'     => $o->guest_name,
            'father_name'    => $o->father_name,
            'guest_phone'    => $o->guest_phone,
            'room_number'    => $o->room_number,
            'order_type'     => $o->order_type,
            'orderTypeBadge' => $o->order_type_badge_class,
            'payment_method' => $o->payment_method,
            'status'         => $o->status,
            'statusBadge'    => $o->status_badge_class,
            'subtotal'       => $o->subtotal,
            'discount'       => $o->discount,
            'tax_percent'    => $o->tax_percent,
            'tax_amount'     => $o->tax_amount,
            'total_amount'   => $o->total_amount,
            'amount_paid'    => $o->amount_paid,
            'balance_due'    => $o->balance_due,
            'notes'          => $o->notes,
            'date'           => optional($o->created_at)->format('d M Y, h:i A'),
            'items'          => $o->items->map(fn ($i) => [
                'item_name'  => $i->item_name,
                'quantity'   => $i->quantity,
                'unit_price' => $i->unit_price,
                'subtotal'   => $i->subtotal,
            ])->values(),
        ];
    }

    private function categoryOptions(): array
    {
        return FoodCategory::with('availableItems')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($c) => [
                'id'    => $c->id,
                'icon'  => $c->icon,
                'name'  => $c->name,
                'items' => $c->availableItems->map(fn ($i) => [
                    'id'          => $i->id,
                    'name'        => $i->name,
                    'description' => $i->description,
                    'price'       => (float) $i->price,
                ])->values(),
            ])->all();
    }

    private function bookingOptions(): array
    {
        return Booking::with(['room', 'customer'])
            ->whereIn('status', ['Confirmed', 'Checked In'])
            ->latest()->get()
            ->map(fn ($b) => [
                'id'          => $b->id,
                'label'       => $b->booking_number . ' — ' . $b->guest_name . ' | Room ' . ($b->room->room_number ?? '?'),
                'guest_name'  => $b->guest_name,
                'father_name' => $b->father_name ?: optional($b->customer)->father_name,
                'guest_phone' => $b->guest_phone,
                'room_number' => $b->room->room_number ?? '',
                'customer_id' => $b->customer_id,
            ])->all();
    }

    private function customerOptions(): array
    {
        return Customer::orderBy('name')->get()->map(fn ($c) => [
            'id'    => $c->id,
            'name'  => $c->name,
            'phone' => $c->phone,
        ])->all();
    }
}
