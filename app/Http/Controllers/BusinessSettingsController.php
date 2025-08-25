<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use App\Services\BusinessSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessSettingsController extends Controller
{
    /**
     * Display business settings page
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $settings = BusinessSetting::getInstance();
        $timezones = BusinessSetting::getTimezones();
        $dateFormats = BusinessSetting::getDateFormats();
        $timeFormats = BusinessSetting::getTimeFormats();
        $currencies = BusinessSetting::getCurrencies();

        return view('business-settings.index', compact(
            'settings',
            'timezones',
            'dateFormats',
            'timeFormats',
            'currencies'
        ));
    }

    /**
     * Update business settings
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_name_ar' => 'required|string|max:255',
            'default_profit_percent' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'currency_symbol_placement' => 'required|in:before,after',
            'timezone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $settings = BusinessSetting::getInstance();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }

            $logoPath = $request->file('logo')->store('business/logos', 'public');
            $settings->logo = $logoPath;
        }

        // Update all settings
        $settings->update($request->except(['logo']));

        // Set application timezone
        config(['app.timezone' => $settings->timezone]);

        // Clear cache
        BusinessSettingsService::clearCache();

        return redirect()->route('business-settings.index')
            ->with('success', 'تم تحديث إعدادات الأعمال بنجاح!');
    }

    /**
     * Remove logo
     */
    public function removeLogo(): \Illuminate\Http\RedirectResponse
    {
        $settings = BusinessSetting::getInstance();

        if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
            Storage::disk('public')->delete($settings->logo);
        }

        $settings->update(['logo' => null]);

        // Clear cache
        BusinessSettingsService::clearCache();

        return redirect()->route('business-settings.index')
            ->with('success', 'تم حذف الشعار بنجاح!');
    }
}
