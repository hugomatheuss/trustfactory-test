<?php

namespace App\Jobs;

use App\Mail\DailySalesReportMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = now()->toDateString();

        $orders = Order::with('items.product')
            ->whereDate('created_at', $today)
            ->where('status', 'completed')
            ->get();

        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();

        $adminEmail = config('app.admin_email', 'admin@example.com');

        Mail::to($adminEmail)->send(new DailySalesReportMail($orders, $totalSales, $totalOrders, $today));
    }
}
