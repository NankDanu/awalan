<?php

namespace App\Helpers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Cache;

class CompanySettingHelper
{
    /**
     * Get the active company setting.
     *
     * @return CompanySetting|null
     */
    public static function getActive(): ?CompanySetting
    {
        return Cache::remember('company_setting_active', 3600, function () {
            return CompanySetting::where('is_active', true)->first();
        });
    }

    /**
     * Get company setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::getActive();
        return $setting?->$key ?? $default;
    }

    /**
     * Get company name.
     *
     * @return string|null
     */
    public static function getCompanyName(): ?string
    {
        return self::get('company_name', 'AWALAN');
    }

    /**
     * Get company email.
     *
     * @return string|null
     */
    public static function getCompanyEmail(): ?string
    {
        return self::get('email');
    }

    /**
     * Get company phone.
     *
     * @return string|null
     */
    public static function getCompanyPhone(): ?string
    {
        return self::get('phone');
    }

    /**
     * Get company logo path.
     *
     * @return string|null
     */
    public static function getLogo(): ?string
    {
        return self::get('logo');
    }

    /**
     * Get company favicon path.
     *
     * @return string|null
     */
    public static function getFavicon(): ?string
    {
        return self::get('favicon');
    }

    /**
     * Get login background path.
     *
     * @return string|null
     */
    public static function getLoginBackground(): ?string
    {
        return self::get('login_background');
    }

    /**
     * Get primary color.
     *
     * @return string
     */
    public static function getPrimaryColor(): string
    {
        return self::get('primary_color', '#3B82F6');
    }

    /**
     * Get secondary color.
     *
     * @return string
     */
    public static function getSecondaryColor(): string
    {
        return self::get('secondary_color', '#10B981');
    }

    /**
     * Clear the cache.
     *
     * @return bool
     */
    public static function clearCache(): bool
    {
        return Cache::forget('company_setting_active');
    }
}
