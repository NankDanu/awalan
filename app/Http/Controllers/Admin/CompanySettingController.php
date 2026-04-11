<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanySettingRequest;
use App\Http\Requests\UpdateCompanySettingRequest;
use App\Models\CompanySetting;
use App\Services\CompanySettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    /**
     * Constructor to inject CompanySettingService.
     */
    public function __construct(
        private CompanySettingService $companySettingService
    ) {}

    /**
     * Display and edit company settings (single page).
     */
    public function index(): View
    {
        // Get active company setting or create new if not exists
        $setting = CompanySetting::where('is_active', true)->first() 
                   ?? CompanySetting::first() 
                   ?? new CompanySetting();
        
        $isNew = !$setting->exists;

        return view('settings.company.index', compact('setting', 'isNew'));
    }

    /**
     * Store a newly created company setting in storage.
     */
    public function store(StoreCompanySettingRequest $request): RedirectResponse
    {
        try {
            $this->companySettingService->create($request->validated());

            return redirect()
                ->route('company-settings.index')
                ->with('success', 'Pengaturan perusahaan berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat pengaturan perusahaan: ' . $e->getMessage());
        }
    }

    /**
     * Update the company setting in storage.
     */
    public function update(UpdateCompanySettingRequest $request): RedirectResponse
    {
        try {
            // Get active setting or first setting
            $setting = CompanySetting::where('is_active', true)->first() 
                       ?? CompanySetting::first();
            
            if (!$setting) {
                return back()->with('error', 'Pengaturan perusahaan tidak ditemukan.');
            }

            $this->companySettingService->update($setting->id, $request->validated());

            return redirect()
                ->route('company-settings.index')
                ->with('success', 'Pengaturan perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan perusahaan: ' . $e->getMessage());
        }
    }
}
