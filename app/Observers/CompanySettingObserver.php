<?php

namespace App\Observers;

use App\Helpers\CompanySettingHelper;
use App\Models\CompanySetting;

class CompanySettingObserver
{
    /**
     * Handle the CompanySetting "created" event.
     */
    public function created(CompanySetting $companySetting): void
    {
        CompanySettingHelper::clearCache();
    }

    /**
     * Handle the CompanySetting "updated" event.
     */
    public function updated(CompanySetting $companySetting): void
    {
        CompanySettingHelper::clearCache();
    }

    /**
     * Handle the CompanySetting "deleted" event.
     */
    public function deleted(CompanySetting $companySetting): void
    {
        CompanySettingHelper::clearCache();
    }

    /**
     * Handle the CompanySetting "restored" event.
     */
    public function restored(CompanySetting $companySetting): void
    {
        CompanySettingHelper::clearCache();
    }

    /**
     * Handle the CompanySetting "force deleted" event.
     */
    public function forceDeleted(CompanySetting $companySetting): void
    {
        CompanySettingHelper::clearCache();
    }
}
