<?php

declare(strict_types=1);

namespace Org\Base\Http\Controllers\Admin;

use Org\Base\Http\Requests\StoreCompanySettingRequest;
use Org\Base\Http\Requests\UpdateCompanySettingRequest;
use Org\Base\Models\CompanySetting;
use Org\Base\Services\CompanySettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function __construct(
        private CompanySettingService $companySettingService
    ) {
        $this->middleware('permission:view-company-settings')->only(['index']);
        $this->middleware('permission:edit-company-settings')->only(['store', 'update']);
    }

    public function index(): View
    {
        $setting = CompanySetting::where('is_active', true)->first()
            ?? CompanySetting::first()
            ?? new CompanySetting();

        $isNew = ! $setting->exists;

        return view('base::base.admin.settings.company.index', compact('setting', 'isNew'));
    }

    public function store(StoreCompanySettingRequest $request): RedirectResponse
    {
        try {
            $this->companySettingService->create($request->validated());

            return redirect()->route('company-settings.index')->with('success', 'Pengaturan perusahaan berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat pengaturan perusahaan: ' . $e->getMessage());
        }
    }

    public function update(UpdateCompanySettingRequest $request): RedirectResponse
    {
        try {
            $setting = CompanySetting::where('is_active', true)->first()
                ?? CompanySetting::first();

            if (! $setting) {
                return back()->with('error', 'Pengaturan perusahaan tidak ditemukan.');
            }

            $this->companySettingService->update($setting->id, $request->validated());

            return redirect()->route('company-settings.index')->with('success', 'Pengaturan perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui pengaturan perusahaan: ' . $e->getMessage());
        }
    }
}
