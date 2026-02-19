<?php

namespace App\Http\Livewire\Orders;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $orders;
    public $selectedOrderId = null;

    public function mount()
    {
        $this->loadOrders();
    }

    private function loadOrders()
    {
        $this->orders = Order::with('items.variant.product')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function markCompleted($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->dispatchBrowserEvent('openCompleteModal');
    }

    public function completeOrder()
    {
        if (!$this->selectedOrderId) {
            return;
        }

        DB::beginTransaction();

        try {

            $order = Order::with('items.variant.product')
                ->find($this->selectedOrderId);

            if (!$order) {
                throw new \Exception('الطلب غير موجود');
            }

            if ($order->status === 'completed') {
                throw new \Exception('الطلب مكتمل مسبقاً');
            }

            // ✅ التحقق من مخزون الـ variant
            foreach ($order->items as $item) {

                $variant = $item->variant;

                if (!$variant) {
                    throw new \Exception("Variant غير موجود");
                }

                if ($variant->stock < $item->quantity) {
                    throw new \Exception(
                        "المخزون غير كافٍ للمنتج: " . $variant->product->name
                    );
                }
            }

            // ✅ إنقاص مخزون الـ variant
            foreach ($order->items as $item) {

                $variant = $item->variant;

                $variant->decrement('stock', $item->quantity);
            }

            // تحديث الحالة
            $order->update([
                'status' => 'completed'
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('notify', [
                'message' => 'تم إنهاء الطلب وإنقاص المخزون بنجاح'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('notify', [
                'message' => $e->getMessage()
            ]);
        }

        $this->selectedOrderId = null;
        $this->dispatchBrowserEvent('closeCompleteModal');
        $this->loadOrders();
    }

    public function render()
    {
        return view('livewire.orders.index')
            ->layout('layouts.app');
    }
}
