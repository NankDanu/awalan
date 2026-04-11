<?php

/**
 * Get active company setting helper functions
 */

use App\Helpers\CompanySettingHelper;

if (!function_exists('company_setting')) {
    /**
     * Get company setting value.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function company_setting(?string $key = null, $default = null)
    {
        if ($key === null) {
            return CompanySettingHelper::getActive();
        }

        return CompanySettingHelper::get($key, $default);
    }
}

if (!function_exists('company_name')) {
    /**
     * Get company name.
     *
     * @return string|null
     */
    function company_name(): ?string
    {
        return CompanySettingHelper::getCompanyName();
    }
}

if (!function_exists('company_email')) {
    /**
     * Get company email.
     *
     * @return string|null
     */
    function company_email(): ?string
    {
        return CompanySettingHelper::getCompanyEmail();
    }
}

if (!function_exists('company_phone')) {
    /**
     * Get company phone.
     *
     * @return string|null
     */
    function company_phone(): ?string
    {
        return CompanySettingHelper::getCompanyPhone();
    }
}

if (!function_exists('company_logo')) {
    /**
     * Get company logo path or URL.
     *
     * @param bool $url Whether to return URL or path
     * @return string|null
     */
    function company_logo(bool $url = true): ?string
    {
        $logo = CompanySettingHelper::getLogo();

        if ($logo && $url) {
            return \Illuminate\Support\Facades\Storage::url($logo);
        }

        return $logo;
    }
}

if (!function_exists('company_favicon')) {
    /**
     * Get company favicon path or URL.
     *
     * @param bool $url Whether to return URL or path
     * @return string|null
     */
    function company_favicon(bool $url = true): ?string
    {
        $favicon = CompanySettingHelper::getFavicon();

        if ($favicon && $url) {
            return \Illuminate\Support\Facades\Storage::url($favicon);
        }

        return $favicon;
    }
}

if (!function_exists('company_login_background')) {
    /**
     * Get login background path or URL.
     *
     * @param bool $url Whether to return URL or path
     * @return string|null
     */
    function company_login_background(bool $url = true): ?string
    {
        $bg = CompanySettingHelper::getLoginBackground();

        if ($bg && $url) {
            return \Illuminate\Support\Facades\Storage::url($bg);
        }

        return $bg;
    }
}

if (!function_exists('primary_color')) {
    /**
     * Get primary color.
     *
     * @return string
     */
    function primary_color(): string
    {
        return CompanySettingHelper::getPrimaryColor();
    }
}

if (!function_exists('secondary_color')) {
    /**
     * Get secondary color.
     *
     * @return string
     */
    function secondary_color(): string
    {
        return CompanySettingHelper::getSecondaryColor();
    }
}
