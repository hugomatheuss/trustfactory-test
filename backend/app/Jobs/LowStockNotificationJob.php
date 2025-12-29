<?php

namespace App\Jobs;

use App\Mail\LowStockAlert;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class LowStockNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Product $product,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $adminEmail = config('app.admin_email', '');
        Mail::to($adminEmail)->send(new LowStockAlert($this->product));
    }
}
