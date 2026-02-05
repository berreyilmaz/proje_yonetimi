<?php

namespace App\Observers;

use App\Models\Operation;
use Carbon\Carbon;

class OperationObserver
{
    /**
     * Handle the Operation "created" event.
     */
    public function created(Operation $operation): void
    {
        //
    }

    /**
     * Handle the Operation "updated" event.
     */

    public function updated(Operation $operation)
    {
        // 8.2 Onay: operasyon ile finans/zaman arasında köprüdür
        if ($operation->isDirty('status') && $operation->status === 'approved') {
            $project = $operation->project;

            if ($operation->type === 'budget') {
                $project->budget += $operation->impact_value;
            } elseif ($operation->type === 'time') {
                $project->end_date = Carbon::parse($project->end_date)->addDays($operation->impact_value);
            }
            
            $project->save();
        }
    }

    /**
     * Handle the Operation "deleted" event.
     */
    public function deleted(Operation $operation): void
    {
        //
    }

    /**
     * Handle the Operation "restored" event.
     */
    public function restored(Operation $operation): void
    {
        //
    }

    /**
     * Handle the Operation "force deleted" event.
     */
    public function forceDeleted(Operation $operation): void
    {
        //
    }
}
