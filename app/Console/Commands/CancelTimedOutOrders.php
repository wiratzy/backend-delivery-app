<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cancelled = \App\Models\Order::where('status', 'pending_confirmation')
            ->where('order_timeout_at', '<=', now())
            ->update(['status' => 'cancelled_timeout']);

        $this->info("Order timeout check: $cancelled order(s) cancelled.");
    }

}
