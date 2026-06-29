<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PruneTestBookings extends Command
{
    protected $signature = 'bookings:prune-test
        {--execute : Actually delete the matched bookings}
        {--force-delete : Permanently delete bookings and their related payments}
        {--older-than= : Only match bookings created before this date, e.g. 2026-06-30}
        {--include-real : Include every booking instead of only obvious test data}';

    protected $description = 'Dry-run or delete local test bookings safely.';

    public function handle(): int
    {
        $query = $this->matchedBookingsQuery();
        $count = (clone $query)->count();

        $this->info($this->option('execute') ? 'Delete mode' : 'Dry-run mode');
        $this->line('Matched bookings: '.$count);

        if ($count === 0) {
            return self::SUCCESS;
        }

        (clone $query)
            ->select(['id', 'booking_code', 'customer_name', 'customer_email', 'created_at'])
            ->latest()
            ->limit(10)
            ->get()
            ->each(function (Booking $booking): void {
                $this->line(sprintf(
                    '- #%d %s | %s | %s | %s',
                    $booking->id,
                    $booking->booking_code,
                    $booking->customer_name,
                    $booking->customer_email,
                    optional($booking->created_at)->format('Y-m-d H:i:s')
                ));
            });

        if (! $this->option('execute')) {
            $this->newLine();
            $this->comment('No data was deleted. Add --execute to delete matched bookings.');

            return self::SUCCESS;
        }

        if ($this->option('include-real') && ! $this->confirm('This includes real-looking bookings. Continue?')) {
            $this->warn('Cancelled.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($query): void {
            $deleted = 0;

            $query->orderBy('id')->chunkById(100, function ($bookings) use (&$deleted): void {
                foreach ($bookings as $booking) {
                    if ($this->option('force-delete')) {
                        $booking->payments()->get()->each->delete();
                        $booking->forceDelete();
                    } else {
                        $booking->delete();
                    }

                    $deleted++;
                }
            });

            $this->info("Deleted bookings: {$deleted}");
        });

        return self::SUCCESS;
    }

    private function matchedBookingsQuery(): Builder
    {
        return Booking::query()
            ->when(! $this->option('include-real'), function (Builder $query): void {
                $query->where(function (Builder $builder): void {
                    $builder
                        ->where('customer_email', 'like', '%@example.%')
                        ->orWhere('customer_email', 'like', 'postman-%')
                        ->orWhere('customer_email', 'like', 'audit-%')
                        ->orWhere('customer_name', 'like', 'Postman%')
                        ->orWhere('customer_name', 'like', 'Browser Replay%')
                        ->orWhere('customer_name', 'like', 'Audit Guest%');
                });
            })
            ->when($this->option('older-than'), function (Builder $query, string $date): void {
                $query->whereDate('created_at', '<', $date);
            });
    }
}
