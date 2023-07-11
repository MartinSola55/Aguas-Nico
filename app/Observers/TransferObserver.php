<?php

namespace App\Observers;

use App\Models\DebtPaymentLog;
use App\Models\Transfer;
use Carbon\Carbon;

class TransferObserver
{
    /**
     * Handle the Transfer "created" event.
     */
    public function created(Transfer $transfer): void
    {
        // Restar la deuda al cliente
        $client = $transfer->client;
        $client->debt -= $transfer->amount;
        $client->save();

        $debtPaymentLog = DebtPaymentLog::firstOrCreate(
            [
                'transfer_id' => $transfer->id,
                'client_id' => $transfer->client_id
            ],
            [
                'transfer_id' => $transfer->id,
                'client_id' => $transfer->client_id,
                'created_at' => Carbon::now(),
            ]
        );
        $debtPaymentLog->debt = ($transfer->amount * -1);
        $debtPaymentLog->updated_at = Carbon::now();
        $debtPaymentLog->save();
    }

    /**
     * Handle the Transfer "updated" event.
     */
    public function updated(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "deleted" event.
     */
    public function deleted(Transfer $transfer): void
    {
        // Restablecer la deuda al cliente
        $client = $transfer->client;
        $client->debt += $transfer->amount;
        DebtPaymentLog::where('transfer_id', $transfer->id)->delete();
        $client->save();
    }

    /**
     * Handle the Transfer "restored" event.
     */
    public function restored(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "force deleted" event.
     */
    public function forceDeleted(Transfer $transfer): void
    {
        //
    }
}
