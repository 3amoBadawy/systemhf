<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessSettingsRequest;
use App\Repositories\Contracts\BusinessSettingRepositoryInterface;
use App\Services\ConfigurationService;

class SystemSettingsController extends Controller
{
    protected BusinessSettingRepositoryInterface $settingRepository;

    protected ConfigurationService $configService;

    public function __construct(
        BusinessSettingRepositoryInterface $settingRepository,
        ConfigurationService $configService
    ) {
        $this->settingRepository = $settingRepository;
        $this->configService = $configService;
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        try {
            $businessSettings = $this->settingRepository->getInstance();
            $timezones = $this->settingRepository->getTimezones();
            $dateFormats = $this->settingRepository->getDateFormats();
            $timeFormats = $this->settingRepository->getTimeFormats();
            $currencies = $this->settingRepository->getCurrencies();
            $settingsByCategory = $this->configService->getEditable();

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

    public function updateBusiness(BusinessSettingsRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                if ($logoFile && $logoFile instanceof \Illuminate\Http\UploadedFile) {
                    $logoPath = $logoFile->store('business/logos', 'public');
                    if ($logoPath !== false) {
                        $this->settingRepository->updateLogo($logoPath);
                    }
                }
            }

            // Update other settings
            unset($data['logo']); // Remove logo from data array
            $this->settingRepository->updateSettings($data);

            return back()->with('success', 'تم تحديث إعدادات الأعمال بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات: '.$e->getMessage());
        }
    }

    public function removeLogo(): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->settingRepository->removeLogo();

            return back()->with('success', 'تم إزالة الشعار بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إزالة الشعار: '.$e->getMessage());
        }
    }

    public function clearCache(): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->configService->clearCache();

            return back()->with('success', 'تم مسح الذاكرة المؤقتة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء مسح الذاكرة المؤقتة: '.$e->getMessage());
        }
    }
}
