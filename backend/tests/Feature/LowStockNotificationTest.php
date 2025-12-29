<?php

namespace Tests\Feature;

use App\Jobs\LowStockNotificationJob;
use App\Mail\LowStockAlert;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LowStockNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_stock_job_is_dispatched_when_stock_crosses_threshold(): void
    {
        Queue::fake();

        $product = Product::factory()->create(['stock_quantity' => 10]);

        $product->update(['stock_quantity' => 5]);

        Queue::assertPushed(LowStockNotificationJob::class, fn($job): bool => $job->product->id === $product->id);
    }

    public function test_low_stock_job_is_not_dispatched_when_stock_stays_above_threshold(): void
    {
        Queue::fake();

        $product = Product::factory()->create(['stock_quantity' => 10]);

        $product->update(['stock_quantity' => 8]);

        Queue::assertNotPushed(LowStockNotificationJob::class);
    }

    public function test_low_stock_job_is_not_dispatched_when_stock_already_below_threshold(): void
    {
        Queue::fake();

        $product = Product::factory()->create(['stock_quantity' => 4]);

        $product->update(['stock_quantity' => 3]);

        Queue::assertNotPushed(LowStockNotificationJob::class);
    }

    public function test_low_stock_job_is_not_dispatched_when_stock_reaches_zero(): void
    {
        Queue::fake();

        $product = Product::factory()->create(['stock_quantity' => 10]);

        $product->update(['stock_quantity' => 0]);

        Queue::assertNotPushed(LowStockNotificationJob::class);
    }

    public function test_low_stock_job_sends_email_to_admin(): void
    {
        Mail::fake();

        $product = Product::factory()->create(['stock_quantity' => 5]);

        $job = new LowStockNotificationJob($product);
        $job->handle();

        Mail::assertSent(LowStockAlert::class, fn($mail) => $mail->hasTo(config('app.admin_email')));
    }

    public function test_low_stock_email_contains_product_information(): void
    {
        Mail::fake();

        $product = Product::factory()->create([
            'name' => 'Test Product',
            'stock_quantity' => 3,
        ]);

        $job = new LowStockNotificationJob($product);
        $job->handle();

        Mail::assertSent(LowStockAlert::class, fn($mail): bool => $mail->product->id === $product->id
            && $mail->product->name === 'Test Product');
    }
}
