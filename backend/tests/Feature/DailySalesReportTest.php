<?php

namespace Tests\Feature;

use App\Jobs\SendDailySalesReportJob;
use App\Mail\DailySalesReportMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DailySalesReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_sales_report_job_sends_email(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total' => 100.00,
        ]);

        $job = new SendDailySalesReportJob();
        $job->handle();

        Mail::assertSent(DailySalesReportMail::class, function ($mail) {
            return $mail->hasTo(config('app.admin_email'));
        });
    }

    public function test_daily_sales_report_only_includes_completed_orders(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total' => 100.00,
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 50.00,
        ]);

        $job = new SendDailySalesReportJob();
        $job->handle();

        Mail::assertSent(DailySalesReportMail::class, function ($mail) {
            return $mail->totalOrders === 1 && $mail->totalSales == 100.00;
        });
    }

    public function test_daily_sales_report_only_includes_todays_orders(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total' => 100.00,
            'created_at' => now(),
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total' => 200.00,
            'created_at' => now()->subDay(),
        ]);

        $job = new SendDailySalesReportJob();
        $job->handle();

        Mail::assertSent(DailySalesReportMail::class, function ($mail) {
            return $mail->totalOrders === 1 && $mail->totalSales == 100.00;
        });
    }

    public function test_daily_sales_report_sends_even_with_no_orders(): void
    {
        Mail::fake();

        $job = new SendDailySalesReportJob();
        $job->handle();

        Mail::assertSent(DailySalesReportMail::class, function ($mail) {
            return $mail->totalOrders === 0 && $mail->totalSales == 0;
        });
    }
}
