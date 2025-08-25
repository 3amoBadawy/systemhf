<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessSettingsRequest;
use App\Repositories\Contracts\BusinessSettingRepositoryInterface;
use App\Services\ConfigurationService;

class SystemSettingsController extends Controller
{
    protected BusinessSettingRepositoryInterface $businessSettingRepository;

    protected ConfigurationService $configurationService;

    public function __construct(
        BusinessSettingRepositoryInterface $businessSettingRepository,
        ConfigurationService $configurationService
    ) {
        $this->businessSettingRepository = $businessSettingRepository;
        $this->configurationService = $configurationService;
    }

    public function index()
    {
        try {
            $businessSettings = $this->businessSettingRepository->getInstance();
            $timezones = $this->businessSettingRepository->getTimezones();
            $dateFormats = $this->businessSettingRepository->getDateFormats();
            $timeFormats = $this->businessSettingRepository->getTimeFormats();
            $currencies = $this->businessSettingRepository->getCurrencies();
            $settingsByCategory = $this->configurationService->getEditable();

            return view('system-settings.index', compact(
                'businessSettings',
                'timezones',
                'dateFormats',
                'timeFormats',
                'currencies',
                'settingsByCategory'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحميل الإعدادات: '.$e->getMessage());
        }
    }

    public function updateBusiness(BusinessSettingsRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('business/logos', 'public');
                $this->businessSettingRepository->updateLogo($logoPath);
            }

            // Update other settings
            unset($data['logo']); // Remove logo from data array
            $this->businessSettingRepository->updateSettings($data);

            return back()->with('success', 'تم تحديث إعدادات الأعمال بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات: '.$e->getMessage());
        }
    }

    public function removeLogo()
    {
        try {
            $this->businessSettingRepository->removeLogo();

            return back()->with('success', 'تم إزالة الشعار بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إزالة الشعار: '.$e->getMessage());
        }
    }

    public function clearCache()
    {
        try {
            $this->configurationService->clearCache();

            return back()->with('success', 'تم مسح الذاكرة المؤقتة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء مسح الذاكرة المؤقتة: '.$e->getMessage());
        }
    }
}
