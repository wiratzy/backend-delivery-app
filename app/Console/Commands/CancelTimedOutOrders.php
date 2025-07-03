<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;


class CancelTimedOutOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-timeout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set status dibatalkan jika order timeout';

    /**
     * Execute the console command.
     */
   public function handle()
    {
        $now = Carbon::now();

        $orders = Order::where('status', 'menunggu_konfirmasi')
            ->where('order_timeout_at', '<', $now)
            ->get();

        foreach ($orders as $order) {
            $order->status = 'dibatalkan';
            $order->save();
            $this->info("Order #{$order->id} dibatalkan karena timeout.");
        }

        return 0;
    }

}
