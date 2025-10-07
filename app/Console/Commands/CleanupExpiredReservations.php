<?php

namespace App\Console\Commands;

use App\Models\Modification\ModificationReservation;
use Illuminate\Console\Command;

class CleanupExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-expired-reservations';

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
        $this->info('Starting cleanup of expired reservations...');

        $count = 0;

        $expiredReservations = ModificationReservation::where('status', 'active')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredReservations as $reservation) {
            // Update reservation status
            $reservation->update(['status' => 'expired']);
            $reservation->po->decrement('in_progress', $reservation->amount);
            $reservation->po->increment('on_hand', $reservation->amount);
            $count++;
        }
        $this->info("Cleaned up {$count} expired reservations.");
        return 0;
    }
}
