<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Modifiers\ScaleDownModifier;

class SettingController extends Controller
{
    private string $key = 'site';
    private array $homeSettingTypes = [
        'clinic' => 'clinic',
        'rac' => 'RAC',
    ];

    public function index(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = Setting::firstOrCreate(['key' => $this->key], ['value' => []]);

        $topbarItem = $this->firstOrCreateHomeSetting('topbar_info', $settingType);
        $topbarV = $topbarItem->value ?? [];
        if (!isset($topbarV['items']) || !is_array($topbarV['items'])) {
            $topbarV['items'] = [];
        }

        $floatingItem = $this->firstOrCreateHomeSetting('floating_info', $settingType);
        $floatingV = $floatingItem->value ?? [];

        $v = $item->value ?? [];

        $normalize = function ($path) {
            return !empty($path)
                ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
                : null;
        };

        // Logo & Favicon — now stored in separate 'site_assets' key
        $assetsItem      = $this->firstOrCreateHomeSetting('site_assets', $settingType);
        $assetsV         = $assetsItem->value ?? [];
        $currentLogoUrl      = $normalize($assetsV['logo'] ?? null);
        $currentLogoBlackUrl = $normalize($assetsV['logo_black'] ?? null);
        $currentFaviconUrl   = $normalize($assetsV['favicon'] ?? null);


        // Các banner
        $currentBannerSlideUrl = $normalize($v['banner_slide'] ?? null);
        $currentBannerScheduleUrl = $normalize($v['banner_schedule'] ?? null);
        $currentBannerAppointmentUrl = $normalize($v['banner_appointment'] ?? null);
        $currentBannerWhyUsUrl = $normalize($v['banner_why_us'] ?? null);
        $currentBannerPatientsUrl = $normalize($v['banner_patients'] ?? null);
        $currentBannerWorkUrl = $normalize($v['banner_work'] ?? null);
        $currentBannerDoctorUrl = $normalize($v['banner_doctor'] ?? null);
        $currentBannerFaqUrl = $normalize($v['banner_faq'] ?? null);

        $servicesItem = $this->firstOrCreateHomeSetting('services_home', $settingType);
        $servicesV = $servicesItem->value ?? [];
        if (!isset($servicesV['items']) || !is_array($servicesV['items'])) {
            $servicesV['items'] = [];
        }

        $whyItem = $this->firstHomeSetting('why_choose_us_home', $settingType);
        $whyChooseUsV = $whyItem ? $whyItem->value : [];
        if (!isset($whyChooseUsV['items']) || !is_array($whyChooseUsV['items'])) {
            $whyChooseUsV['items'] = [];
        }
        $whyChooseUsV['currentImageUrl'] = !empty($whyChooseUsV['image']) ? asset($whyChooseUsV['image']) : null;

        $testimonialsItem = $this->firstHomeSetting('testimonials_home', $settingType);
        $testimonialsV = $testimonialsItem ? $testimonialsItem->value : [];
        if (!isset($testimonialsV['items']) || !is_array($testimonialsV['items'])) {
            $testimonialsV['items'] = [];
        }

        $faqItem = $this->firstHomeSetting('faq_home', $settingType);
        $faqV = $faqItem ? $faqItem->value : [];
        if (!isset($faqV['items']) || !is_array($faqV['items'])) {
            $faqV['items'] = [];
        }

        // Extra tabs moved from Trang Chủ to Cấu Hình Chung
        $utilitiesTabData = $this->getHomeSectionData('utilities', $settingType);
        $doctorTabData    = $this->getHomeSectionData('doctor', $settingType);
        $specialistsTabData = $this->getHomeSectionData('specialists', $settingType);
        $appointmentTabData = $this->getHomeSectionData('appointment', $settingType);

        return view(module() . '.main', compact(
            'item',
            'currentLogoUrl',
            'currentLogoBlackUrl',
            'currentFaviconUrl',
            'currentBannerSlideUrl',
            'currentBannerScheduleUrl',
            'currentBannerAppointmentUrl',
            'currentBannerWhyUsUrl',
            'currentBannerPatientsUrl',
            'currentBannerWorkUrl',
            'currentBannerDoctorUrl',
            'currentBannerFaqUrl',
            'settingType',
            'topbarV',
            'floatingV',
            'servicesV',
            'whyChooseUsV',
            'testimonialsV',
            'faqV',
            'utilitiesTabData',
            'doctorTabData',
            'specialistsTabData',
            'appointmentTabData'
        ));
    }

    public function slide_home()
    {

        $validate = [
            'email'                 => ['nullable', 'string'],
            'phone'                 => ['nullable', 'string'],
            'address'               => ['nullable', 'string'],
            'content'               => ['nullable', 'string'],
        ];

        // return [
        //     ''
        // ]
    }

    public function update(Request $request, $domain, $id)
    {
        $item = Setting::where('id', $id)->where('key', $this->key)->firstOrFail();

        $data = $request->validate([
            // Common fields
            'company'               => ['nullable', 'string'],
            'description'           => ['nullable', 'string'],
            'copyright'             => ['nullable', 'string'],
            'map'                   => ['nullable', 'string'],
            // Email group
            'email_icon'            => ['nullable', 'string'],
            'email_title'           => ['nullable', 'string'],
            'email_description'     => ['nullable', 'string'],
            'email_placeholder'     => ['nullable', 'string'],
            // Phone group
            'phone_icon'            => ['nullable', 'string'],
            'phone_title'           => ['nullable', 'string'],
            'phone_description'     => ['nullable', 'string'],
            // Address group
            'address_icon'          => ['nullable', 'string'],
            'address_title'         => ['nullable', 'string'],
            'address_description'   => ['nullable', 'string'],
            // Time group
            'time_icon'             => ['nullable', 'string'],
            'time_title'            => ['nullable', 'string'],
            'time_description'      => ['nullable', 'string'],
            // Buttons
            'btn_appointment_title' => ['nullable', 'string'],
            'btn_appointment_link'  => ['nullable', 'string'],
            'btn_register_title'    => ['nullable', 'string'],
            // Contact & Register
            'contact_title'         => ['nullable', 'string'],
            'contact_description'   => ['nullable', 'string'],
            'register_title'        => ['nullable', 'string'],
            'register_description'  => ['nullable', 'string'],
            // Sidebar
            'sidebar_title_one'     => ['nullable', 'string'],
            'sidebar_title_two'     => ['nullable', 'string'],
            'sidebar_title_three'   => ['nullable', 'string'],
            'label_contact'         => ['nullable', 'string'],
            'label_email'           => ['nullable', 'string'],
            'placeholder_email'     => ['nullable', 'string'],
            'label_follow'          => ['nullable', 'string'],
        ]);

        $v = $item->value ?? [];

        // Only text fields — images are handled by updateLogoFavicon()
        $payload = [
            'company'               => $data['company'] ?? $v['company'] ?? null,
            'description'           => $data['description'] ?? $v['description'] ?? null,
            'copyright'             => $data['copyright'] ?? $v['copyright'] ?? null,
            'map'                   => $data['map'] ?? $v['map'] ?? null,
            // Email group
            'email_icon'            => $data['email_icon'] ?? $v['email_icon'] ?? null,
            'email_title'           => $data['email_title'] ?? $v['email_title'] ?? null,
            'email_description'     => $data['email_description'] ?? $v['email_description'] ?? null,
            'email_placeholder'     => $data['email_placeholder'] ?? $v['email_placeholder'] ?? null,
            // Phone group
            'phone_icon'            => $data['phone_icon'] ?? $v['phone_icon'] ?? null,
            'phone_title'           => $data['phone_title'] ?? $v['phone_title'] ?? null,
            'phone_description'     => $data['phone_description'] ?? $v['phone_description'] ?? null,
            // Address group
            'address_icon'          => $data['address_icon'] ?? $v['address_icon'] ?? null,
            'address_title'         => $data['address_title'] ?? $v['address_title'] ?? null,
            'address_description'   => $data['address_description'] ?? $v['address_description'] ?? null,
            // Time group
            'time_icon'             => $data['time_icon'] ?? $v['time_icon'] ?? null,
            'time_title'            => $data['time_title'] ?? $v['time_title'] ?? null,
            'time_description'      => $data['time_description'] ?? $v['time_description'] ?? null,
            // Buttons
            'btn_appointment_title' => $data['btn_appointment_title'] ?? $v['btn_appointment_title'] ?? null,
            'btn_appointment_link'  => $data['btn_appointment_link'] ?? $v['btn_appointment_link'] ?? null,
            'btn_register_title'    => $data['btn_register_title'] ?? $v['btn_register_title'] ?? null,
            // Contact & Register
            'contact_title'         => $data['contact_title'] ?? $v['contact_title'] ?? null,
            'contact_description'   => $data['contact_description'] ?? $v['contact_description'] ?? null,
            'register_title'        => $data['register_title'] ?? $v['register_title'] ?? null,
            'register_description'  => $data['register_description'] ?? $v['register_description'] ?? null,
            // Sidebar
            'sidebar_title_one'     => $data['sidebar_title_one'] ?? $v['sidebar_title_one'] ?? null,
            'sidebar_title_two'     => $data['sidebar_title_two'] ?? $v['sidebar_title_two'] ?? null,
            'sidebar_title_three'   => $data['sidebar_title_three'] ?? $v['sidebar_title_three'] ?? null,
            'label_contact'         => $data['label_contact'] ?? $v['label_contact'] ?? null,
            'label_email'           => $data['label_email'] ?? $v['label_email'] ?? null,
            'placeholder_email'     => $data['placeholder_email'] ?? $v['placeholder_email'] ?? null,
            'label_follow'          => $data['label_follow'] ?? $v['label_follow'] ?? null,
        ];

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json(['message' => 'Đã lưu cài đặt.', 'key' => $item->key, 'value' => $payload])
                : redirect()->to(panel_route(module() . '.index'))->with('success', 'Đã lưu cài đặt.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Setting update failed: " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    public function updateLogoFavicon(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = $this->firstOrCreateHomeSetting('site_assets', $settingType);
        $v    = $item->value ?? [];

        $domainParam    = $request->route('domain') ?? 'default';
        $domainSlug     = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domainParam));
        $moduleName     = module() ?? 'setting';
        $uploadDir      = "uploads/{$domainSlug}/{$moduleName}";
        $expectedPrefix = "uploads/{$domainSlug}/";

        $imageFields = [
            'logo'       => ['file_key' => 'logo_file',       'remove_key' => 'remove_logo_file'],
            'logo_black' => ['file_key' => 'logo_black_file', 'remove_key' => 'remove_logo_black_file'],
            'favicon'    => ['file_key' => 'favicon_file',    'remove_key' => 'remove_favicon_file'],
        ];

        $payload  = $v;
        $newFiles = [];

        foreach ($imageFields as $field => $keys) {
            $oldPath = $v[$field] ?? null;
            $newPath = null;

            if ($request->hasFile($keys['file_key'])) {
                $file     = $request->file($keys['file_key']);
                $ext      = $file->getClientOriginalExtension();
                $filename = "{$field}_" . time() . '_' . uniqid() . '.' . $ext;
                $newPath  = $file->storeAs($uploadDir, $filename, 'public');
                $payload[$field] = $newPath;
                $newFiles[] = $newPath;
            }

            if ($request->boolean($keys['remove_key']) && !$newPath) {
                $payload[$field] = null;
            }

            if ($oldPath && ($newPath || $request->boolean($keys['remove_key']))) {
                if (str_starts_with($oldPath, $expectedPrefix) && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json(['message' => 'Đã lưu Logo & Favicon.', 'key' => $item->key, 'value' => $payload])
                : redirect()->to(panel_route('setting.index') . '?tab=logo')->with('success', 'Đã lưu Logo & Favicon.');
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($newFiles as $p) {
                if (Storage::disk('public')->exists($p)) Storage::disk('public')->delete($p);
            }
            Log::error('Logo Favicon update failed: ' . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    public function updateTopbar(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = $this->firstOrCreateHomeSetting('topbar_info', $settingType);

        $data = $request->validate([
            'items'               => ['nullable', 'array'],
            'items.*.title'       => ['nullable', 'string'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.link'        => ['nullable', 'string'],
            'items.*.image'       => ['nullable', 'string'],
        ]);

        $domainParam = $request->route('domain') ?? 'default';
        $domainSlug  = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domainParam));
        $moduleName  = module() ?? 'setting';
        $uploadDir   = "uploads/{$domainSlug}/{$moduleName}";

        $payload = ['items' => []];

        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $index => $itemData) {
                if (empty($itemData['title']) && empty($itemData['description']) && empty($itemData['link'])) {
                    continue;
                }

                $imagePath = $itemData['image'] ?? '';

                // Handle uploaded image file for this item
                $uploadedFile = $request->file("items.{$index}.image_file");
                if ($uploadedFile) {
                    $ext      = $uploadedFile->getClientOriginalExtension();
                    $filename = "topbar_item_{$index}_" . time() . '_' . uniqid() . '.' . $ext;
                    $imagePath = $uploadedFile->storeAs($uploadDir, $filename, 'public');
                }

                $payload['items'][] = [
                    'title'       => $itemData['title'] ?? '',
                    'description' => $itemData['description'] ?? '',
                    'link'        => $itemData['link'] ?? '',
                    'image'       => $imagePath,
                ];
            }
        }

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => 'Đã lưu cài đặt topbar.',
                    'key'     => $item->key,
                    'value'   => $payload,
                ])
                : redirect()->to(panel_route('setting.index') . '?type=' . $settingType . '&tab=topbar')->with('success', 'Đã lưu cài đặt topbar.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Topbar Setting update failed: " . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    public function updateServices(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = $this->firstOrCreateHomeSetting('services_home', $settingType);

        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'subtitle'          => ['nullable', 'string'],
            'view_all_link'     => ['nullable', 'string'],
            'items'             => ['nullable', 'array'],
            'items.*.title'     => ['nullable', 'string'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.doctor_text' => ['nullable', 'string'],
            'items.*.link'      => ['nullable', 'string'],
        ]);

        $payload = [
            'title'         => $data['title'] ?? null,
            'subtitle'      => $data['subtitle'] ?? null,
            'view_all_link' => $data['view_all_link'] ?? null,
            'items'         => [],
        ];

        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                if (empty($itemData['title']) && empty($itemData['description']) && empty($itemData['doctor_text']) && empty($itemData['link'])) {
                    continue;
                }
                $payload['items'][] = [
                    'title'       => $itemData['title'] ?? '',
                    'description' => $itemData['description'] ?? '',
                    'doctor_text' => $itemData['doctor_text'] ?? '',
                    'link'        => $itemData['link'] ?? '',
                ];
            }
        }

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => 'Đã lưu cài đặt dịch vụ.',
                    'key'     => $item->key,
                    'value'   => $payload,
                ])
                : redirect()->to(panel_route('setting.index') . '?type=' . $settingType . '&tab=services')->with('success', 'Đã lưu cài đặt dịch vụ.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Services Setting update failed: " . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    public function updateFloating(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = $this->firstOrCreateHomeSetting('floating_info', $settingType);

        $data = $request->validate([
            'emergency_text' => ['nullable', 'string'],
            'emergency_link' => ['nullable', 'string'],
            'contact_phone'  => ['nullable', 'string'],
            'contact_link'   => ['nullable', 'string'],
            'socials'        => ['nullable', 'array'],
            'socials.*.name' => ['nullable', 'string'],
            'socials.*.link' => ['nullable', 'string'],
            'socials.*.icon' => ['nullable', 'string'],
        ]);

        $payload = [
            'emergency_text' => $data['emergency_text'] ?? '',
            'emergency_link' => $data['emergency_link'] ?? '',
            'contact_phone'  => $data['contact_phone'] ?? '',
            'contact_link'   => $data['contact_link'] ?? '',
            'socials'        => [],
        ];

        if (!empty($data['socials']) && is_array($data['socials'])) {
            foreach ($data['socials'] as $soc) {
                if (empty($soc['name']) && empty($soc['link'])) continue;
                $payload['socials'][] = [
                    'name' => $soc['name'] ?? '',
                    'link' => $soc['link'] ?? '',
                    'icon' => $soc['icon'] ?? '',
                ];
            }
        }

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => 'Đã lưu cài đặt tiện ích nổi bật.',
                    'key'     => $item->key,
                    'value'   => $payload,
                ])
                : redirect()->to(panel_route('setting.index') . '?type=' . $settingType . '&tab=floating')->with('success', 'Đã lưu cài đặt tiện ích nổi bật.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Floating Setting update failed: " . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    public function hero()
    {
        $item = Setting::firstOrCreate(['key' => 'hero_home'], ['value' => []]);
        $v = $item->value ?? [];

        $normalize = function ($path) {
            return !empty($path)
                ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
                : null;
        };

        $currentBannerHeroUrl = $normalize($v['banner_hero'] ?? null);

        return view(module() . '.hero', compact('item', 'currentBannerHeroUrl'));
    }

    public function updateHero(Request $request, $domain, $id)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = $this->firstOrCreateHomeSetting('hero_home', $settingType);

        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'button_one_text'   => ['nullable', 'string'],
            'button_one_link'   => ['nullable', 'string'],
            'button_two_text'   => ['nullable', 'string'],
            'button_two_link'   => ['nullable', 'string'],
            'question_title'    => ['nullable', 'string'],
            'question_email'    => ['nullable', 'string'],
            'percent'           => ['nullable', 'string'],
            'percent_text'      => ['nullable', 'string'],
            'percent_link'      => ['nullable', 'string'],
            'patient_title'     => ['nullable', 'string'],
            'patient_des'       => ['nullable', 'string'],
            'banner_hero_file'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:10240'],
            'remove_banner_hero_file' => ['nullable', 'boolean'],
        ]);

        $v = $item->value ?? [];
        $payload = array_merge($v, [
            'title'             => $data['title'] ?? null,
            'description'       => $data['description'] ?? null,
            'button_one_text'   => $data['button_one_text'] ?? null,
            'button_one_link'   => $data['button_one_link'] ?? null,
            'button_two_text'   => $data['button_two_text'] ?? null,
            'button_two_link'   => $data['button_two_link'] ?? null,
            'question_title'    => $data['question_title'] ?? null,
            'question_email'    => $data['question_email'] ?? null,
            'percent'           => $data['percent'] ?? null,
            'percent_text'      => $data['percent_text'] ?? null,
            'percent_link'      => $data['percent_link'] ?? null,
            'patient_title'     => $data['patient_title'] ?? null,
            'patient_des'       => $data['patient_des'] ?? null,
        ]);

        // Handle file upload
        $domainParam = $domain ?? 'default';
        $domainSlug = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domainParam));
        $moduleName = module() ?? 'setting';
        $uploadDir = "uploads/{$domainSlug}/{$moduleName}";
        $expectedPrefix = "uploads/{$domainSlug}/";

        if ($request->hasFile('banner_hero_file')) {
            $file = $request->file('banner_hero_file');
            $ext = $file->getClientOriginalExtension();
            $filename = "hero_banner_" . time() . '_' . uniqid() . '.' . $ext;
            $newPath = $file->storeAs($uploadDir, $filename, 'public');

            // Delete old file if exists
            if (!empty($v['banner_hero']) && Storage::disk('public')->exists($v['banner_hero'])) {
                Storage::disk('public')->delete($v['banner_hero']);
            }
            $payload['banner_hero'] = $newPath;
        } elseif ($request->boolean('remove_banner_hero_file')) {
            if (!empty($v['banner_hero']) && Storage::disk('public')->exists($v['banner_hero'])) {
                Storage::disk('public')->delete($v['banner_hero']);
            }
            $payload['banner_hero'] = null;
        }

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => 'Đã lưu cài đặt hero.',
                    'key'     => $item->key,
                    'value'   => $payload,
                ])
                : redirect()->to(panel_route('setting.hero') . '?type=' . $settingType)->with('success', 'Đã lưu cài đặt hero.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Hero Setting update failed: " . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    public function utilities()
    {
        $item = Setting::firstOrCreate(['key' => 'utilities_home'], ['value' => []]);
        $v = $item->value ?? [];

        $normalize = function ($path) {
            return !empty($path)
                ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
                : null;
        };

        $currentImageOneUrl = $normalize($v['image_one'] ?? null);
        $currentImageTwoUrl = $normalize($v['image_two'] ?? null);

        // Default open hours if empty
        if (empty($v['open_hours'])) {
            $v['open_hours'] = [
                ['day' => 'Monday', 'time' => '09:30 - 07:30'],
                ['day' => 'Tuesday', 'time' => '09:30 - 07:30'],
                ['day' => 'Wednesday', 'time' => '09:30 - 07:30'],
                ['day' => 'Thursday', 'time' => '09:30 - 07:30'],
                ['day' => 'Friday', 'time' => '09:30 - 07:30'],
                ['day' => 'Saturday', 'time' => '09:30 - 07:30'],
            ];
        }

        return view(module() . '.utilities', compact('item', 'currentImageOneUrl', 'currentImageTwoUrl', 'v'));
    }

    public function updateUtilities(Request $request, $domain)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = $this->firstOrCreateHomeSetting('utilities_home', $settingType);

        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'button_text'       => ['nullable', 'string'],
            'button_link'       => ['nullable', 'string'],
            'phone'             => ['nullable', 'string'],
            'image_one_file'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:10240'],
            'image_two_file'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:10240'],
            'remove_image_one'  => ['nullable', 'boolean'],
            'remove_image_two'  => ['nullable', 'boolean'],
            'open_hours'        => ['nullable', 'array'],
            'utilities'         => ['nullable', 'array'],
        ]);

        $v = $item->value ?? [];
        $payload = array_merge($v, [
            'title'             => $data['title'] ?? null,
            'description'       => $data['description'] ?? null,
            'button_text'       => $data['button_text'] ?? null,
            'button_link'       => $data['button_link'] ?? null,
            'phone'             => $data['phone'] ?? null,
            'open_hours'        => $data['open_hours'] ?? [],
            'utilities'         => $data['utilities'] ?? [],
        ]);

        // Handle file uploads
        $domainParam = $domain ?? 'default';
        $domainSlug = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domainParam));
        $moduleName = module() ?? 'setting';
        $uploadDir = "uploads/{$domainSlug}/{$moduleName}";

        $imageFields = [
            'image_one' => 'image_one_file',
            'image_two' => 'image_two_file',
        ];

        foreach ($imageFields as $field => $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$field}_" . time() . '_' . uniqid() . '.' . $ext;
                $newPath = $file->storeAs($uploadDir, $filename, 'public');

                // Delete old file if exists
                if (!empty($v[$field]) && Storage::disk('public')->exists($v[$field])) {
                    Storage::disk('public')->delete($v[$field]);
                }
                $payload[$field] = $newPath;
            } elseif ($request->boolean("remove_{$field}")) {
                if (!empty($v[$field]) && Storage::disk('public')->exists($v[$field])) {
                    Storage::disk('public')->delete($v[$field]);
                }
                $payload[$field] = null;
            }
        }

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => 'Đã lưu cài đặt tiện ích.',
                    'key'     => $item->key,
                    'value'   => $payload,
                ])
                : redirect()->to(panel_route('setting.utilities') . '?type=' . $settingType)->with('success', 'Đã lưu cài đặt tiện ích.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Utilities Setting update failed: " . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }
    public function stats()
    {
        $item = Setting::firstOrCreate(['key' => 'stats_home'], ['value' => []]);
        $v = $item->value ?? [];

        $normalize = function ($path) {
            return !empty($path)
                ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
                : null;
        };

        $currentAvatarUrls = [];
        if (!empty($v['avatar_group']['images'])) {
            foreach ($v['avatar_group']['images'] as $img) {
                $currentAvatarUrls[] = $normalize($img);
            }
        }

        $backgroundUrl = $normalize($v['background'] ?? null);

        return view(module() . '.stats', compact('item', 'v', 'currentAvatarUrls', 'backgroundUrl'));
    }

    public function updateStats(Request $request, $domain)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $item = $this->firstOrCreateHomeSetting('stats_home', $settingType);

        $data = $request->validate([
            'avatar_group_text'   => ['nullable', 'string'],
            'avatar_group_files'  => ['nullable', 'array'],
            'avatar_group_files.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'remove_avatars'      => ['nullable', 'array'],
            'stat_numbers'        => ['nullable', 'array'],
            'stat_labels'         => ['nullable', 'array'],
            'background'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ]);

        $v = $item->value ?? [];
        $payload = [
            'background'   => $v['background'] ?? null,
            'avatar_group' => [
                'images' => $v['avatar_group']['images'] ?? [],
                'text'   => $data['avatar_group_text'] ?? null,
            ],
            'items' => []
        ];

        // Handle Stat Items
        if (!empty($data['stat_numbers'])) {
            foreach ($data['stat_numbers'] as $index => $number) {
                $payload['items'][] = [
                    'number' => $number,
                    'label'  => $data['stat_labels'][$index] ?? '',
                ];
            }
        }

        // Handle File Uploads
        $domainParam = $domain ?? 'default';
        $domainSlug = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domainParam));
        $moduleName = module() ?? 'setting';
        $uploadDir = "uploads/{$domainSlug}/{$moduleName}";

        // Handle Background Image
        if ($request->hasFile('background')) {
            if (!empty($v['background'])) {
                if (Storage::disk('public')->exists($v['background'])) {
                    Storage::disk('public')->delete($v['background']);
                }
            }
            $file = $request->file('background');
            $ext = $file->getClientOriginalExtension();
            $filename = "stats_bg_" . time() . '.' . $ext;
            $payload['background'] = $file->storeAs($uploadDir, $filename, 'public');
        }

        // Remove marked avatars
        if (!empty($data['remove_avatars'])) {
            foreach ($data['remove_avatars'] as $index) {
                if (isset($payload['avatar_group']['images'][$index])) {
                    $oldPath = $payload['avatar_group']['images'][$index];
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                    unset($payload['avatar_group']['images'][$index]);
                }
            }
            $payload['avatar_group']['images'] = array_values($payload['avatar_group']['images']);
        }

        // Add new avatars
        if ($request->hasFile('avatar_group_files')) {
            if (!is_array($payload['avatar_group']['images'])) {
                $payload['avatar_group']['images'] = [];
            }
            foreach ($request->file('avatar_group_files') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = "avatar_" . time() . '_' . uniqid() . '.' . $ext;
                $newPath = $file->storeAs($uploadDir, $filename, 'public');
                $payload['avatar_group']['images'][] = $newPath;
            }
        }

        DB::beginTransaction();
        try {
            $item->value = $payload;
            $item->save();
            DB::commit();

            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => 'Đã lưu cài đặt thống kê.',
                    'key'     => $item->key,
                    'value'   => $payload,
                ])
                : redirect()->to(panel_route('setting.stats') . '?type=' . $settingType)->with('success', 'Đã lưu cài đặt thống kê.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Stats Setting update failed: " . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    public function removeStatAvatar(Request $request, $domain, $index)
    {
        $item = $this->firstHomeSetting('stats_home', $request->input('type'));
        if (!$item) {
            return response()->json(['message' => 'Không tìm thấy cài đặt.'], 404);
        }

        $v = $item->value ?? [];
        if (!isset($v['avatar_group']['images'][$index])) {
            return response()->json(['message' => 'Không tìm thấy ảnh.'], 404);
        }

        $path = $v['avatar_group']['images'][$index];

        // Delete file from storage
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Remove from array and re-index
        unset($v['avatar_group']['images'][$index]);
        $v['avatar_group']['images'] = array_values($v['avatar_group']['images'] ?? []);

        $item->value = $v;
        $item->save();

        return response()->json(['message' => 'Đã xóa ảnh thành công.']);
    }

    public function services()
    {
        $item = Setting::firstOrCreate(['key' => 'services_home'], ['value' => []]);
        $v = $item->value ?? [];

        // Ensure we always have 8 items for the view, even if empty
        if (!isset($v['items']) || !is_array($v['items'])) {
            $v['items'] = array_fill(0, 8, ['title' => '', 'description' => '', 'doctor_text' => '', 'link' => '']);
        } else {
            // Pad to 8 items if fewer exist
            while (count($v['items']) < 8) {
                $v['items'][] = ['title' => '', 'description' => '', 'doctor_text' => '', 'link' => ''];
            }
        }

        return view(module() . '.services', compact('item', 'v'));
    }

    public function appointment()
    {
        $item = Setting::where('key', 'appointment_home')->first();
        $v = $item ? $item->value : [];
        $currentImageUrl = !empty($v['image']) ? asset($v['image']) : null;

        return view('setting.appointment', compact('v', 'currentImageUrl'));
    }

    public function updateAppointment(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'                => ['nullable', 'string'],
            'subtitle'             => ['nullable', 'string'],
            'appointment_now_text' => ['nullable', 'string'],
            'button_text'          => ['nullable', 'string'],
            'button_link'          => ['nullable', 'string'],
            'image_file'           => ['nullable', 'image', 'max:2048'],
        ]);

        $item = $this->firstHomeSetting('appointment_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'                => $data['title'] ?? '',
            'subtitle'             => $data['subtitle'] ?? '',
            'appointment_now_text' => $data['appointment_now_text'] ?? '',
            'button_text'          => $data['button_text'] ?? '',
            'button_link'          => $data['button_link'] ?? '',
            'image'                => $v['image'] ?? ''
        ];

        if ($request->hasFile('image_file')) {
            if (!empty($v['image']) && file_exists(public_path($v['image']))) {
                @unlink(public_path($v['image']));
            }
            $file = $request->file('image_file');
            $filename = 'appointment_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['image'] = 'uploads/settings/' . $filename;
        }

        $savedItem = $this->updateOrCreateHomeSetting('appointment_home', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt lịch hẹn.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function whyChooseUs()
    {
        $item = Setting::where('key', 'why_choose_us_home')->first();
        $v = $item ? $item->value : [];

        $currentImageUrl = !empty($v['image']) ? asset($v['image']) : null;

        if (!isset($v['items']) || !is_array($v['items'])) {
            $v['items'] = [];
        }

        return view('setting.why_choose_us', compact('v', 'currentImageUrl'));
    }

    public function updateWhyChooseUs(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'experience_number' => ['nullable', 'string'],
            'experience_label'  => ['nullable', 'string'],
            'image_file'        => ['nullable', 'image', 'max:2048'],
            'items.*.title'     => ['nullable', 'string'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        $item = $this->firstHomeSetting('why_choose_us_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'             => $data['title'] ?? '',
            'experience_number' => $data['experience_number'] ?? '',
            'experience_label'  => $data['experience_label'] ?? '',
            'image'             => $v['image'] ?? '',
            'items'             => []
        ];

        // Process dynamic feature items (bảng + modal)
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $payload['items'][] = [
                    'title'       => $itemData['title'] ?? '',
                    'description' => $itemData['description'] ?? '',
                ];
            }
        }

        if ($request->hasFile('image_file')) {
            if (!empty($v['image']) && file_exists(public_path($v['image']))) {
                @unlink(public_path($v['image']));
            }
            $file = $request->file('image_file');
            $filename = 'why_choose_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['image'] = 'uploads/settings/' . $filename;
        }

        $savedItem = $this->updateOrCreateHomeSetting('why_choose_us_home', $payload, $settingType);

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'message' => 'Đã lưu cài đặt Tại sao chọn chúng tôi.',
                'key'     => $savedItem->key,
                'value'   => $payload,
            ])
            : redirect()->to(panel_route('setting.index') . '?type=' . $settingType . '&tab=why_choose_us')->with('success', 'Đã lưu cài đặt Tại sao chọn chúng tôi.');
    }

    public function specialists()
    {
        $item = Setting::where('key', 'specialists_home')->first();
        $v = $item ? $item->value : [];

        if (!isset($v['items']) || !is_array($v['items'])) {
            $v['items'] = [];
        }

        return view('setting.specialists', compact('v'));
    }

    public function updateSpecialists(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'view_all_link'     => ['nullable', 'string'],
            'items.*.name'      => ['nullable', 'string'],
            'items.*.specialty' => ['nullable', 'string'],
            'items.*.button_text' => ['nullable', 'string'],
            'items.*.button_link' => ['nullable', 'string'],
            'items.*.socials.linkedin' => ['nullable', 'string'],
            'items.*.socials.facebook' => ['nullable', 'string'],
            'items.*.socials.twitter'  => ['nullable', 'string'],
            'items.*.socials.youtube'  => ['nullable', 'string'],
            'items.*.image_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $item = $this->firstHomeSetting('specialists_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'         => $data['title'] ?? '',
            'view_all_link' => $data['view_all_link'] ?? '',
            'items'         => []
        ];

        $items = $data['items'] ?? [];
        foreach ($items as $i => $itemData) {
            $oldItem = $v['items'][$i] ?? [];
            $newItem = [
                'name'        => $itemData['name'] ?? '',
                'specialty'   => $itemData['specialty'] ?? '',
                'button_text' => $itemData['button_text'] ?? '',
                'button_link' => $itemData['button_link'] ?? '',
                'socials'     => $itemData['socials'] ?? [],
                'image'       => $oldItem['image'] ?? ''
            ];

            if ($request->hasFile("items.$i.image_file")) {
                if (!empty($newItem['image']) && file_exists(public_path($newItem['image']))) {
                    @unlink(public_path($newItem['image']));
                }
                $file = $request->file("items.$i.image_file");
                $filename = 'specialist_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $newItem['image'] = 'uploads/settings/' . $filename;
            }

            $payload['items'][] = $newItem;
        }

        $savedItem = $this->updateOrCreateHomeSetting('specialists_home', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt bác sĩ.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function testimonials()
    {
        $item = Setting::where('key', 'testimonials_home')->first();
        $v = $item ? $item->value : [];

        // Ensure defaults for dynamic items
        if (!isset($v['items']) || !is_array($v['items'])) {
            $v['items'] = [];
        }

        return view('setting.testimonials', compact('v'));
    }

    public function updateTestimonials(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'main_title'               => ['nullable', 'string'],
            'main_image_file'          => ['nullable', 'image', 'max:2048'],
            // Floating review
            'floating_review.name'     => ['nullable', 'string'],
            'floating_review.rating'   => ['nullable', 'numeric', 'min:1', 'max:5'],
            'floating_review.text'     => ['nullable', 'string'],
            'floating_review_avatar'   => ['nullable', 'image', 'max:2048'],
            // Achievement
            'achievement.number'       => ['nullable', 'string'],
            'achievement.text'         => ['nullable', 'string'],
            'achievement_avatars.*'    => ['nullable', 'image', 'max:2048'],
            // Dynamic Items
            'items.*.name'             => ['nullable', 'string'],
            'items.*.role'             => ['nullable', 'string'],
            'items.*.title'            => ['nullable', 'string'],
            'items.*.review'           => ['nullable', 'string'],
            'items.*.video_link'       => ['nullable', 'string'],
            'items.*.image_file'       => ['nullable', 'image', 'max:2048'],
        ]);

        $item = $this->firstHomeSetting('testimonials_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'main_title'      => $data['main_title'] ?? '',
            'main_image'      => $v['main_image'] ?? '',
            'floating_review' => [
                'name'   => $data['floating_review']['name'] ?? '',
                'rating' => $data['floating_review']['rating'] ?? 5,
                'text'   => $data['floating_review']['text'] ?? '',
                'avatar' => $v['floating_review']['avatar'] ?? '',
            ],
            'achievement'     => [
                'number'  => $data['achievement']['number'] ?? '',
                'text'    => $data['achievement']['text'] ?? '',
                'avatars' => $v['achievement']['avatars'] ?? ['', '', '', ''],
            ],
            'items'           => []
        ];

        // Handle Main Image
        if ($request->hasFile('main_image_file')) {
            if (!empty($v['main_image']) && file_exists(public_path($v['main_image']))) {
                @unlink(public_path($v['main_image']));
            }
            $file = $request->file('main_image_file');
            $filename = 'testimonial_main_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['main_image'] = 'uploads/settings/' . $filename;
        }

        // Handle Floating Review Avatar
        if ($request->hasFile('floating_review_avatar')) {
            if (!empty($v['floating_review']['avatar']) && file_exists(public_path($v['floating_review']['avatar']))) {
                @unlink(public_path($v['floating_review']['avatar']));
            }
            $file = $request->file('floating_review_avatar');
            $filename = 'testimonial_float_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['floating_review']['avatar'] = 'uploads/settings/' . $filename;
        }

        // Handle Achievement Avatars
        for ($i = 0; $i < 4; $i++) {
            if ($request->hasFile("achievement_avatars.$i")) {
                if (!empty($v['achievement']['avatars'][$i]) && file_exists(public_path($v['achievement']['avatars'][$i]))) {
                    @unlink(public_path($v['achievement']['avatars'][$i]));
                }
                $file = $request->file("achievement_avatars.$i");
                $filename = 'testimonial_achieve_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $payload['achievement']['avatars'][$i] = 'uploads/settings/' . $filename;
            }
        }

        // Handle Dynamic Items
        if (isset($data['items'])) {
            foreach ($data['items'] as $index => $itemData) {
                $oldItem = $v['items'][$index] ?? null;
                $newItem = [
                    'name'       => $itemData['name'] ?? '',
                    'role'       => $itemData['role'] ?? '',
                    'title'      => $itemData['title'] ?? '',
                    'review'     => $itemData['review'] ?? '',
                    'video_link' => $itemData['video_link'] ?? '',
                    'image'      => $oldItem['image'] ?? '',
                ];

                if ($request->hasFile("items.$index.image_file")) {
                    if (!empty($newItem['image']) && file_exists(public_path($newItem['image']))) {
                        @unlink(public_path($newItem['image']));
                    }
                    $file = $request->file("items.$index.image_file");
                    $filename = 'testimonial_item_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/settings'), $filename);
                    $newItem['image'] = 'uploads/settings/' . $filename;
                }
                $payload['items'][] = $newItem;
            }
        }

        $savedItem = $this->updateOrCreateHomeSetting('testimonials_home', $payload, $settingType);

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'message' => 'Đã lưu cài đặt ý kiến khách hàng.',
                'key'     => $savedItem->key,
                'value'   => $payload,
            ])
            : redirect()->to(panel_route('setting.index') . '?type=' . $settingType . '&tab=testimonials')->with('success', 'Đã lưu cài đặt ý kiến khách hàng.');
    }

    public function howItWork()
    {
        $item = Setting::where('key', 'how_it_work_home')->first();
        $v = $item ? $item->value : [];

        if (!isset($v['features']) || !is_array($v['features'])) {
            $v['features'] = [];
        }
        if (!isset($v['stats']) || !is_array($v['stats'])) {
            $v['stats'] = [];
        }

        return view('setting.how_it_work', compact('v'));
    }

    public function updateHowItWork(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'                 => ['nullable', 'string'],
            'description'           => ['nullable', 'string'],
            'image_file'            => ['nullable', 'image', 'max:2048'],
            'appointment_btn_text'  => ['nullable', 'string'],
            'appointment_btn_link'  => ['nullable', 'string'],
            // Dynamic Features
            'features.*.icon'       => ['nullable', 'string'],
            'features.*.title'      => ['nullable', 'string'],
            // Dynamic Stats
            'stats.*.number'        => ['nullable', 'string'],
            'stats.*.label'         => ['nullable', 'string'],
        ]);

        $item = $this->firstHomeSetting('how_it_work_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'           => $data['title'] ?? '',
            'description'     => $data['description'] ?? '',
            'image'           => $v['image'] ?? '',
            'appointment_btn' => [
                'text' => $data['appointment_btn_text'] ?? '',
                'link' => $data['appointment_btn_link'] ?? '',
            ],
            'features'        => [],
            'stats'           => []
        ];

        // Handle Main Image
        if ($request->hasFile('image_file')) {
            if (!empty($v['image']) && file_exists(public_path($v['image']))) {
                @unlink(public_path($v['image']));
            }
            $file = $request->file('image_file');
            $filename = 'how_it_work_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['image'] = 'uploads/settings/' . $filename;
        }

        // Handle Dynamic Features
        if (isset($data['features'])) {
            foreach ($data['features'] as $f) {
                $payload['features'][] = [
                    'icon'  => $f['icon'] ?? '',
                    'title' => $f['title'] ?? '',
                ];
            }
        }

        // Handle Dynamic Stats
        if (isset($data['stats'])) {
            foreach ($data['stats'] as $s) {
                $payload['stats'][] = [
                    'number' => $s['number'] ?? '',
                    'label'  => $s['label'] ?? '',
                ];
            }
        }

        $savedItem = $this->updateOrCreateHomeSetting('how_it_work_home', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt cách thức hoạt động.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function doctor()
    {
        $item = Setting::where('key', 'doctor_home')->first();
        $v = $item ? $item->value : [];

        if (!isset($v['skills']) || !is_array($v['skills'])) {
            $v['skills'] = [];
        }
        if (!isset($v['achievements']) || !is_array($v['achievements'])) {
            $v['achievements'] = [];
        }

        return view('setting.doctor', compact('v'));
    }

    public function updateDoctor(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'doctor_name'       => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'skills_header'     => ['nullable', 'string'],
            'image_file'        => ['nullable', 'image', 'max:2048'],
            // Experience
            'experience.number' => ['nullable', 'string'],
            'experience.label'  => ['nullable', 'string'],
            // Dynamic Skills
            'skills.*'          => ['nullable', 'string'],
            // Dynamic Achievements
            'achievements.*.title'       => ['nullable', 'string'],
            'achievements.*.subtitle'    => ['nullable', 'string'],
            'achievements.*.link_text'   => ['nullable', 'string'],
            'achievement_images.*'       => ['nullable', 'image', 'max:2048'],
        ]);

        $item = $this->firstHomeSetting('doctor_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'         => $data['title'] ?? '',
            'doctor_name'   => $data['doctor_name'] ?? '',
            'description'   => $data['description'] ?? '',
            'skills_header' => $data['skills_header'] ?? '',
            'image'         => $v['image'] ?? '',
            'experience'    => [
                'number' => $data['experience']['number'] ?? '',
                'label'  => $data['experience']['label'] ?? '',
            ],
            'skills'        => array_values($data['skills'] ?? []),
            'achievements'  => []
        ];

        // Handle Main Image
        if ($request->hasFile('image_file')) {
            if (!empty($v['image']) && file_exists(public_path($v['image']))) {
                @unlink(public_path($v['image']));
            }
            $file = $request->file('image_file');
            $filename = 'doctor_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['image'] = 'uploads/settings/' . $filename;
        }

        // Handle Dynamic Achievements
        if (isset($request->achievements)) {
            $imgs = $request->file('achievement_images') ?? [];
            foreach ($request->achievements as $index => $ach) {
                // Keep old image or get new one
                $imgPath = $v['achievements'][$index]['image'] ?? '';

                if (isset($imgs[$index])) {
                    // Delete old
                    if (!empty($imgPath) && file_exists(public_path($imgPath))) {
                        @unlink(public_path($imgPath));
                    }
                    $file = $imgs[$index];
                    $filename = 'achievement_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/settings'), $filename);
                    $imgPath = 'uploads/settings/' . $filename;
                }

                $payload['achievements'][] = [
                    'image'     => $imgPath,
                    'title'     => $ach['title'] ?? '',
                    'subtitle'  => $ach['subtitle'] ?? '',
                    'link_text' => $ach['link_text'] ?? '',
                ];
            }
        }

        $savedItem = $this->updateOrCreateHomeSetting('doctor_home', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt bác sĩ.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function faq()
    {
        $item = Setting::where('key', 'faq_home')->first();
        $v = $item ? $item->value : [];

        if (!isset($v['items']) || !is_array($v['items'])) {
            $v['items'] = [];
        }

        return view('setting.faq', compact('v'));
    }

    public function updateFaq(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'                 => ['nullable', 'string'],
            'description'           => ['nullable', 'string'],
            'image_file'            => ['nullable', 'image', 'max:2048'],
            // Contact
            'contact.text'          => ['nullable', 'string'],
            'contact.phone'         => ['nullable', 'string'],
            // Appointment
            'appointment_btn_text'  => ['nullable', 'string'],
            'appointment_btn_link'  => ['nullable', 'string'],
            // Dynamic FAQ Items
            'items.*.question'      => ['nullable', 'string'],
            'items.*.answer'        => ['nullable', 'string'],
        ]);

        $item = $this->firstHomeSetting('faq_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'           => $data['title'] ?? '',
            'description'     => $data['description'] ?? '',
            'image'           => $v['image'] ?? '',
            'contact'         => [
                'text'  => $data['contact']['text'] ?? '',
                'phone' => $data['contact']['phone'] ?? '',
            ],
            'appointment_btn' => [
                'text'     => $data['appointment_btn_text'] ?? '',
                'link'     => $data['appointment_btn_link'] ?? '',
            ],
            'items'           => [],
        ];

        // Handle Main Image
        if ($request->hasFile('image_file')) {
            if (!empty($v['image']) && file_exists(public_path($v['image']))) {
                @unlink(public_path($v['image']));
            }
            $file = $request->file('image_file');
            $filename = 'faq_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['image'] = 'uploads/settings/' . $filename;
        }

        // Handle Dynamic Items
        if (isset($data['items'])) {
            foreach ($data['items'] as $it) {
                $payload['items'][] = [
                    'question' => $it['question'] ?? '',
                    'answer'   => $it['answer'] ?? '',
                ];
            }
        }

        $savedItem = $this->updateOrCreateHomeSetting('faq_home', $payload, $settingType);

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'message' => 'Đã lưu cài đặt hỏi đáp.',
                'key'     => $savedItem->key,
                'value'   => $payload,
            ])
            : redirect()->to(panel_route('setting.index') . '?type=' . $settingType . '&tab=faq')->with('success', 'Đã lưu cài đặt hỏi đáp.');
    }

    public function awards()
    {
        $item = Setting::where('key', 'awards_home')->first();
        $v = $item ? $item->value : [];

        if (!isset($v['years']) || !is_array($v['years'])) {
            $v['years'] = [];
        }
        if (!isset($v['items']) || !is_array($v['items'])) {
            $v['items'] = [];
        }

        return view('setting.awards', compact('v'));
    }

    public function updateAwards(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'years.*'           => ['nullable', 'string'],
            // Awards items
            'items.*.title'     => ['nullable', 'string'],
            'items.*.subtitle'  => ['nullable', 'string'],
            'items.*.link_text' => ['nullable', 'string'],
            'items.*.year'      => ['nullable', 'string'],
            'award_images.*'    => ['nullable', 'image', 'max:1024'],
        ]);

        $item = $this->firstHomeSetting('awards_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'       => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'years'       => array_values(array_filter($data['years'] ?? [])),
            'items'       => []
        ];

        if (isset($request->items)) {
            $imgs = $request->file('award_images') ?? [];
            foreach ($request->items as $index => $award) {
                $imgPath = $v['items'][$index]['image'] ?? '';

                if (isset($imgs[$index])) {
                    if (!empty($imgPath) && file_exists(public_path($imgPath))) {
                        @unlink(public_path($imgPath));
                    }
                    $file = $imgs[$index];
                    $filename = 'award_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/settings'), $filename);
                    $imgPath = 'uploads/settings/' . $filename;
                }

                $payload['items'][] = [
                    'image'     => $imgPath,
                    'title'     => $award['title'] ?? '',
                    'subtitle'  => $award['subtitle'] ?? '',
                    'link_text' => $award['link_text'] ?? '',
                    'year'      => $award['year'] ?? '',
                ];
            }
        }

        $savedItem = $this->updateOrCreateHomeSetting('awards_home', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt giải thưởng.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function blogs()
    {
        $item = Setting::where('key', 'blogs_home')->first();
        $v = $item ? $item->value : [];

        if (!isset($v['items']) || !is_array($v['items'])) {
            $v['items'] = [];
        }

        return view('setting.blogs', compact('v'));
    }

    public function updateBlogs(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'             => ['nullable', 'string'],
            'view_all_text'     => ['nullable', 'string'],
            'view_all_link'     => ['nullable', 'string'],
            // Blog items
            'items.*.date'      => ['nullable', 'string'],
            'items.*.title'     => ['nullable', 'string'],
            'items.*.link_text' => ['nullable', 'string'],
            'items.*.link'      => ['nullable', 'string'],
            'blog_images.*'     => ['nullable', 'image', 'max:2048'],
        ]);

        $item = $this->firstHomeSetting('blogs_home', $settingType);
        $v = $item ? $item->value : [];

        $payload = [
            'title'    => $data['title'] ?? '',
            'view_all' => [
                'text' => $data['view_all_text'] ?? '',
                'link' => $data['view_all_link'] ?? '',
            ],
            'items'    => []
        ];

        if (isset($request->items)) {
            $imgs = $request->file('blog_images') ?? [];
            foreach ($request->items as $index => $blog) {
                $imgPath = $v['items'][$index]['image'] ?? '';

                if (isset($imgs[$index])) {
                    if (!empty($imgPath) && file_exists(public_path($imgPath))) {
                        @unlink(public_path($imgPath));
                    }
                    $file = $imgs[$index];
                    $filename = 'blog_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/settings'), $filename);
                    $imgPath = 'uploads/settings/' . $filename;
                }

                $payload['items'][] = [
                    'image'     => $imgPath,
                    'date'      => $blog['date'] ?? '',
                    'title'     => $blog['title'] ?? '',
                    'link_text' => $blog['link_text'] ?? '',
                    'link'      => $blog['link'] ?? '',
                ];
            }
        }

        $savedItem = $this->updateOrCreateHomeSetting('blogs_home', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt tin tức.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function contact()
    {
        $item = Setting::where('key', 'contact_home')->first();
        $v = $item ? $item->value : [];

        return view('setting.contact', compact('v'));
    }

    public function updateContact(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'                => ['nullable', 'string'],
            'description'          => ['nullable', 'string'],
            // Address
            'address.label'        => ['nullable', 'string'],
            'address.value'        => ['nullable', 'string'],
            // Phone
            'phone.label'          => ['nullable', 'string'],
            'phone.value'          => ['nullable', 'string'],
            // Email
            'email.label'          => ['nullable', 'string'],
            'email.value'          => ['nullable', 'string'],
            // Time
            'time.label'           => ['nullable', 'string'],
            'time.value'           => ['nullable', 'string'],
            // Appointment
            'appointment_btn.text' => ['nullable', 'string'],
            'appointment_btn.link' => ['nullable', 'string'],
            // Map
            'map_iframe'           => ['nullable', 'string'],
        ]);

        $payload = [
            'title'           => $data['title'] ?? '',
            'description'     => $data['description'] ?? '',
            'address'         => [
                'label' => $data['address']['label'] ?? '',
                'value' => $data['address']['value'] ?? '',
            ],
            'phone'           => [
                'label' => $data['phone']['label'] ?? '',
                'value' => $data['phone']['value'] ?? '',
            ],
            'email'           => [
                'label' => $data['email']['label'] ?? '',
                'value' => $data['email']['value'] ?? '',
            ],
            'time'            => [
                'label' => $data['time']['label'] ?? '',
                'value' => $data['time']['value'] ?? '',
            ],
            'appointment_btn' => [
                'text'  => $data['appointment_btn']['text'] ?? '',
                'link'  => $data['appointment_btn']['link'] ?? '',
            ],
            'map_iframe'      => $data['map_iframe'] ?? '',
        ];

        $savedItem = $this->updateOrCreateHomeSetting('contact_home', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt liên hệ.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    /**
     * Trang cấu hình trang chủ: layout 2 cột (navbar dọc trái + nội dung phải).
     */
    public function home()
    {
        $settingType = $this->resolveHomeSettingType();
        $sectionsList = [
            ['key' => 'hero',         'label' => 'Phần Đầu'],
            ['key' => 'stats',        'label' => 'Thống kê'],
            ['key' => 'appointment',  'label' => 'Lịch hẹn'],
            ['key' => 'how_it_work',  'label' => 'Cách thức hoạt động'],
            ['key' => 'awards',       'label' => 'Bằng cấp'],
        ];
        $allowed = array_column($sectionsList, 'key');
        $section = request('section', 'hero');
        if (!in_array($section, $allowed)) {
            $section = 'hero';
        }
        $sectionData = $this->getHomeSectionData($section, $settingType);

        // Chuẩn bị dữ liệu cho tất cả section để dùng vertical tab không reload
        $sectionsData = [];
        foreach ($sectionsList as $item) {
            $key = $item['key'];
            $sectionsData[$key] = $this->getHomeSectionData($key, $settingType);
        }

        return view('setting.home', [
            'currentSection' => $section,
            'settingType'    => $settingType,
            'settingTypes'   => $this->homeSettingTypes,
            'sectionsList'   => $sectionsList,
            'sectionData'    => $sectionData,
            'sectionsData'   => $sectionsData,
        ]);
    }

    public function service()
    {
        $settingType = $this->resolveHomeSettingType();
        $sectionsList = [
            ['key' => 'hero',  'label' => 'Phần đầu'],
            ['key' => 'plans', 'label' => 'Kế hoạch'],
        ];

        $allowed = array_column($sectionsList, 'key');
        $section = request('section', 'hero');
        if (!in_array($section, $allowed)) {
            $section = 'hero';
        }

        // Tái sử dụng logic lấy dữ liệu giống bên trang chủ, nhưng key sẽ là service_hero_home thay vì hero_home
        // Wait, để tách biệt dữ liệu "Banner Header" của Dịch vụ, ta dùng key 'service_hero' thay vì 'hero_home'.

        $normalize = function ($path) {
            return !empty($path)
                ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
                : null;
        };

        $sectionsData = [];
        foreach ($sectionsList as $item) {
            $key = $item['key'];
            if ($key === 'hero') {
                $sectionItem = $this->firstOrCreateHomeSetting('service_hero', $settingType);
                $v = $sectionItem->value ?? [];
                $sectionsData[$key] = [
                    'item' => $sectionItem,
                    'currentBannerHeroUrl' => $normalize($v['banner_hero'] ?? null),
                    'v' => $v
                ];
            } elseif ($key === 'plans') {
                $sectionItem = $this->firstOrCreateHomeSetting('service_plans', $settingType);
                $v = $sectionItem->value ?? [];
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                // NORMALIZE đường dẫn ảnh cho từng item trong plans
                foreach ($v['items'] as &$planItem) {
                    if (!empty($planItem['image_url'])) {
                        $planItem['image_url'] = $normalize($planItem['image_url']);
                    }
                }
                $sectionsData[$key] = [
                    "item" => $sectionItem,
                    "v" => $v,
                ];
            }
        }

        $sectionData = $sectionsData[$section] ?? [];

        return view('setting.service', [
            'currentSection' => $section,
            'settingType'    => $settingType,
            'settingTypes'   => $this->homeSettingTypes,
            'sectionsList'   => $sectionsList,
            'sectionData'    => $sectionData,
            'sectionsData'   => $sectionsData,
        ]);
    }

    public function updateServiceHero(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'banner_hero_file' => ['nullable', 'image', 'max:5120'],
        ]);

        $item = $this->firstOrCreateHomeSetting('service_hero', $settingType);
        $v = $item->value ?? [];

        $payload = [
            'banner_hero' => $v['banner_hero'] ?? '',
        ];

        if ($request->hasFile('banner_hero_file')) {
            if (!empty($v['banner_hero']) && file_exists(public_path($v['banner_hero']))) {
                @unlink(public_path($v['banner_hero']));
            }
            $file = $request->file('banner_hero_file');
            $filename = 'service_hero_banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['banner_hero'] = 'uploads/settings/' . $filename;
        }

        $savedItem = $this->updateOrCreateHomeSetting('service_hero', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt Banner Header.',
            'key' => $savedItem->key,
            'value' => $payload,
        ]);
    }

    public function updateServicePlans(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));

        $data = $request->validate([
            'title' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],

            'features_pool' => ['nullable', 'array'],
            'features_pool.*' => ['nullable', 'string'],

            'items' => ['nullable', 'array'],
            'items.*.name' => ['nullable', 'string'],
            'items.*.price' => ['nullable', 'string'],
            'items.*.period' => ['nullable', 'string'],
            'items.*.btn_text' => ['nullable', 'string'],
            'items.*.btn_link' => ['nullable', 'string'],
            'items.*.features' => ['nullable', 'array'],
            'items.*.features.*' => ['nullable', 'string'],

            'items.*.treatment_steps' => ['nullable', 'array'],
            'items.*.treatment_steps.*.step_number' => ['nullable', 'string'],
            'items.*.treatment_steps.*.title' => ['nullable', 'string'],
            'items.*.treatment_steps.*.description' => ['nullable', 'string'],

            'items.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'items.*.image_url' => ['nullable', 'string'],
        ]);

        $payload = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'features_pool' => array_values(array_filter($data['features_pool'] ?? [])),
            'items' => [],
        ];

        foreach (($data['items'] ?? []) as $index => $it) {
            $imageUrl = $it['image_url'] ?? '';

            if (!empty($imageUrl)) {
                $parsedPath = parse_url($imageUrl, PHP_URL_PATH);
                $imageUrl = ltrim($parsedPath, '/');
            }

            if ($request->hasFile("items.$index.image")) {
                if (!empty($imageUrl) && file_exists(public_path($imageUrl))) {
                    @unlink(public_path($imageUrl));
                }

                $file = $request->file("items.$index.image");
                $filename = 'plan_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);

                $imageUrl = 'uploads/settings/' . $filename;
            }

            $treatmentSteps = collect($it['treatment_steps'] ?? [])
                ->filter(function ($step) {
                    return !empty($step['step_number'])
                        || !empty($step['title'])
                        || !empty($step['description']);
                })
                ->map(function ($step) {
                    return [
                        'step_number' => $step['step_number'] ?? '',
                        'title' => $step['title'] ?? '',
                        'description' => $step['description'] ?? '',
                    ];
                })
                ->values()
                ->toArray();

            $payload['items'][] = [
                'name' => $it['name'] ?? '',
                'price' => $it['price'] ?? '',
                'period' => $it['period'] ?? '',
                'btn_text' => $it['btn_text'] ?? '',
                'btn_link' => $it['btn_link'] ?? '',
                'features' => array_values($it['features'] ?? []),
                'image_url' => $imageUrl,
                'treatment_steps' => $treatmentSteps,
            ];
        }

        $savedItem = $this->updateOrCreateHomeSetting('service_plans', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu cài đặt bảng giá dịch vụ.',
            'key' => $savedItem->key,
            'value' => $payload,
        ]);
    }


    /**
     * Lấy dữ liệu cho từng section của trang cấu hình trang chủ.
     */
    private function resolveHomeSettingType(?string $type = null): string
    {
        $resolved = strtolower(trim((string) ($type ?? request('type', 'clinic'))));

        return array_key_exists($resolved, $this->homeSettingTypes) ? $resolved : 'clinic';
    }

    private function getTypedHomeSettingKey(string $baseKey, ?string $type = null): string
    {
        return $baseKey . '_' . $this->resolveHomeSettingType($type);
    }

    private function firstHomeSetting(string $baseKey, ?string $type = null): ?Setting
    {
        return Setting::where('key', $this->getTypedHomeSettingKey($baseKey, $type))->first()
            ?? Setting::where('key', $baseKey)->first();
    }

    private function firstOrCreateHomeSetting(string $baseKey, ?string $type = null): Setting
    {
        $typedKey = $this->getTypedHomeSettingKey($baseKey, $type);
        $existing = Setting::where('key', $typedKey)->first();

        if ($existing) {
            return $existing;
        }

        $legacy = Setting::where('key', $baseKey)->first();

        return Setting::create([
            'key' => $typedKey,
            'value' => $legacy->value ?? [],
        ]);
    }

    private function updateOrCreateHomeSetting(string $baseKey, array $payload, ?string $type = null): Setting
    {
        return Setting::updateOrCreate(
            ['key' => $this->getTypedHomeSettingKey($baseKey, $type)],
            ['value' => $payload]
        );
    }

    private function getHomeSectionData(string $section, ?string $type = null): array
    {
        $normalize = function ($path) {
            return !empty($path)
                ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
                : null;
        };

        switch ($section) {
            case 'hero':
                $item = $this->firstOrCreateHomeSetting('hero_home', $type);
                $v = $item->value ?? [];
                return ['item' => $item, 'currentBannerHeroUrl' => $normalize($v['banner_hero'] ?? null)];

            case 'utilities':
                $item = $this->firstOrCreateHomeSetting('utilities_home', $type);
                $v = $item->value ?? [];
                if (empty($v['open_hours'])) {
                    $v['open_hours'] = [
                        ['day' => 'Monday', 'time' => '09:30 - 07:30'],
                        ['day' => 'Tuesday', 'time' => '09:30 - 07:30'],
                        ['day' => 'Wednesday', 'time' => '09:30 - 07:30'],
                        ['day' => 'Thursday', 'time' => '09:30 - 07:30'],
                        ['day' => 'Friday', 'time' => '09:30 - 07:30'],
                        ['day' => 'Saturday', 'time' => '09:30 - 07:30'],
                    ];
                }
                return [
                    'item' => $item,
                    'currentImageOneUrl' => $normalize($v['image_one'] ?? null),
                    'currentImageTwoUrl' => $normalize($v['image_two'] ?? null),
                    'v' => $v,
                ];

            case 'stats':
                $item = $this->firstOrCreateHomeSetting('stats_home', $type);
                $v = $item->value ?? [];
                $currentAvatarUrls = [];
                if (!empty($v['avatar_group']['images'])) {
                    foreach ($v['avatar_group']['images'] as $img) {
                        $currentAvatarUrls[] = $normalize($img);
                    }
                }
                return [
                    'item' => $item,
                    'v' => $v,
                    'currentAvatarUrls' => $currentAvatarUrls,
                    'backgroundUrl' => $normalize($v['background'] ?? null),
                ];

            case 'services':
                $item = $this->firstOrCreateHomeSetting('services_home', $type);
                $v = $item->value ?? [];
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                return ['item' => $item, 'v' => $v];

            case 'appointment':
                $item = $this->firstHomeSetting('appointment_home', $type);
                $v = $item ? $item->value : [];
                $currentImageUrl = !empty($v['image']) ? asset($v['image']) : null;
                return ['v' => $v, 'currentImageUrl' => $currentImageUrl];

            case 'why_choose_us':
                $item = $this->firstHomeSetting('why_choose_us_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                $currentImageUrl = !empty($v['image']) ? asset($v['image']) : null;
                return ['v' => $v, 'currentImageUrl' => $currentImageUrl];

            case 'specialists':
                $item = $this->firstHomeSetting('specialists_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                return ['v' => $v];

            case 'testimonials':
                $item = $this->firstHomeSetting('testimonials_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                return ['v' => $v];

            case 'how_it_work':
                $item = $this->firstHomeSetting('how_it_work_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['features']) || !is_array($v['features'])) {
                    $v['features'] = [];
                }
                if (!isset($v['stats']) || !is_array($v['stats'])) {
                    $v['stats'] = [];
                }
                return ['v' => $v];

            case 'doctor':
                $item = $this->firstHomeSetting('doctor_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['skills']) || !is_array($v['skills'])) {
                    $v['skills'] = [];
                }
                if (!isset($v['achievements']) || !is_array($v['achievements'])) {
                    $v['achievements'] = [];
                }
                return ['v' => $v];

            case 'faq':
                $item = $this->firstHomeSetting('faq_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                return ['v' => $v];

            case 'awards':
                $item = $this->firstHomeSetting('awards_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['years']) || !is_array($v['years'])) {
                    $v['years'] = [];
                }
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                return ['v' => $v];

            case 'blogs':
                $item = $this->firstHomeSetting('blogs_home', $type);
                $v = $item ? $item->value : [];
                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }
                return ['v' => $v];

            case 'contact':
                $item = $this->firstHomeSetting('contact_home', $type);
                $v = $item ? $item->value : [];
                return ['v' => $v];

            default:
                $item = $this->firstOrCreateHomeSetting('hero_home', $type);
                $v = $item->value ?? [];
                return ['item' => $item, 'currentBannerHeroUrl' => $normalize($v['banner_hero'] ?? null)];
        }
    }
    public function contactPage()
    {
        $settingType = $this->resolveHomeSettingType();

        $sectionsList = [
            ['key' => 'hero',      'label' => 'Phần đầu'],
            ['key' => 'info',      'label' => 'Thông Tin & Form Liên Hệ'],
            ['key' => 'locations', 'label' => 'Vị Trí'],
        ];

        $allowed = array_column($sectionsList, 'key');

        $section = request('section', 'hero');

        if (!in_array($section, $allowed, true)) {
            $section = 'hero';
        }

        $normalize = function ($path) {
            return !empty($path)
                ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
                : null;
        };

        $sectionsData = [];

        foreach ($sectionsList as $item) {
            $key = $item['key'];

            if ($key === 'hero') {
                $sectionItem = $this->firstOrCreateHomeSetting('contact_page_hero', $settingType);
                $v = $sectionItem->value ?? [];

                $sectionsData[$key] = [
                    'item' => $sectionItem,
                    'currentBannerHeroUrl' => $normalize($v['banner_hero'] ?? null),
                    'v' => $v,
                ];
            }

            if ($key === 'info') {
                $sectionItem = $this->firstOrCreateHomeSetting('contact_page_info', $settingType);
                $v = $sectionItem->value ?? [];

                $sectionsData[$key] = [
                    'item' => $sectionItem,
                    'v' => $v,
                ];
            }

            if ($key === 'locations') {
                $sectionItem = $this->firstOrCreateHomeSetting('contact_page_locations', $settingType);
                $v = $sectionItem->value ?? [];

                if (!isset($v['items']) || !is_array($v['items'])) {
                    $v['items'] = [];
                }

                $sectionsData[$key] = [
                    'item' => $sectionItem,
                    'v' => $v,
                ];
            }
        }

        $sectionData = $sectionsData[$section] ?? [];

        return view('setting.contact-page', [
            'currentSection' => $section,
            'settingType' => $settingType,
            'settingTypes' => $this->homeSettingTypes,
            'sectionsList' => $sectionsList,
            'sectionData' => $sectionData,
            'sectionsData' => $sectionsData,
        ]);
    }

    public function updateContactHero(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'banner_hero_file' => ['nullable', 'image', 'max:5120'],
        ]);

        $item = $this->firstOrCreateHomeSetting('contact_page_hero', $settingType);
        $v = $item->value ?? [];

        $payload = [
            'banner_hero' => $v['banner_hero'] ?? '',
        ];

        if ($request->hasFile('banner_hero_file')) {
            if (!empty($v['banner_hero']) && file_exists(public_path($v['banner_hero']))) {
                @unlink(public_path($v['banner_hero']));
            }
            $file = $request->file('banner_hero_file');
            $filename = 'contact_hero_banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['banner_hero'] = 'uploads/settings/' . $filename;
        }

        $savedItem = $this->updateOrCreateHomeSetting('contact_page_hero', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu Banner Header liên hệ.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function updateContactInfo(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'title'                => ['nullable', 'string'],
            'description'          => ['nullable', 'string'],
            'address.label'        => ['nullable', 'string'],
            'address.value'        => ['nullable', 'string'],
            'phone.label'          => ['nullable', 'string'],
            'phone.value'          => ['nullable', 'string'],
            'email.label'          => ['nullable', 'string'],
            'email.value'          => ['nullable', 'string'],
            'time.label'           => ['nullable', 'string'],
            'time.value'           => ['nullable', 'string'],
            'stats_text'           => ['nullable', 'string'],
            'rating.score'         => ['nullable', 'string'],
            'rating.text'          => ['nullable', 'string'],
            'appointment_btn.text' => ['nullable', 'string'],
            'appointment_btn.link' => ['nullable', 'string'],
            'form_title'           => ['nullable', 'string'],
            'form_subtitle'        => ['nullable', 'string'],
        ]);

        $payload = [
            'title'           => $data['title'] ?? '',
            'description'     => $data['description'] ?? '',
            'address'         => ['label' => $data['address']['label'] ?? '', 'value' => $data['address']['value'] ?? ''],
            'phone'           => ['label' => $data['phone']['label'] ?? '',   'value' => $data['phone']['value'] ?? ''],
            'email'           => ['label' => $data['email']['label'] ?? '',   'value' => $data['email']['value'] ?? ''],
            'time'            => ['label' => $data['time']['label'] ?? '',    'value' => $data['time']['value'] ?? ''],
            'stats_text'      => $data['stats_text'] ?? '',
            'rating'          => ['score' => $data['rating']['score'] ?? '', 'text' => $data['rating']['text'] ?? ''],
            'appointment_btn' => ['text' => $data['appointment_btn']['text'] ?? '', 'link' => $data['appointment_btn']['link'] ?? ''],
            'form_title'      => $data['form_title'] ?? '',
            'form_subtitle'   => $data['form_subtitle'] ?? '',
        ];

        $savedItem = $this->updateOrCreateHomeSetting('contact_page_info', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu thông tin liên hệ.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    public function updateContactLocations(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $data = $request->validate([
            'section_title'         => ['nullable', 'string'],
            'items.*.name'          => ['nullable', 'string'],
            'items.*.address'       => ['nullable', 'string'],
            'items.*.service_time'  => ['nullable', 'string'],
            'items.*.google_link'   => ['nullable', 'string'],
            'items.*.map_iframe'    => ['nullable', 'string'],
        ]);

        $payload = [
            'section_title' => $data['section_title'] ?? '',
            'items'         => [],
        ];

        foreach ($data['items'] ?? [] as $it) {
            $payload['items'][] = [
                'name'         => $it['name'] ?? '',
                'address'      => $it['address'] ?? '',
                'service_time' => $it['service_time'] ?? '',
                'google_link'  => $it['google_link'] ?? '',
                'map_iframe'   => $it['map_iframe'] ?? '',
            ];
        }

        $savedItem = $this->updateOrCreateHomeSetting('contact_page_locations', $payload, $settingType);

        return response()->json([
            'message' => 'Đã lưu danh sách vị trí.',
            'key'     => $savedItem->key,
            'value'   => $payload,
        ]);
    }

    // ─── About Page ──────────────────────────────────────────────────────────

    public function aboutPage()
    {
        $settingType  = $this->resolveHomeSettingType();
        $sectionsList = [
            ['key' => 'hero',           'label' => 'Phần Đầu'],
            ['key' => 'gallery',        'label' => 'Thư Viện Ảnh'],
            ['key' => 'vision_mission', 'label' => 'Tầm Nhìn & Sứ Mệnh'],
            ['key' => 'consultation',   'label' => 'Đặt Lịch Tư Vấn'],
            ['key' => 'insurance',      'label' => 'Thông Tin Bảo Hiểm'],
        ];
        $allowed = array_column($sectionsList, 'key');
        $section = request('section', 'hero');
        if (!in_array($section, $allowed)) $section = 'hero';

        $normalize = fn($path) => !empty($path)
            ? (function_exists('normalize_image_url') ? normalize_image_url($path, 'setting') : $path)
            : null;

        $sectionsData = [];
        foreach ($sectionsList as $listItem) {
            $key         = $listItem['key'];
            $settingItem = $this->firstOrCreateHomeSetting('about_page_' . $key, $settingType);
            $v           = $settingItem->value ?? [];

            if ($key === 'hero') {
                $sectionsData[$key] = [
                    'item'                 => $settingItem,
                    'currentBannerHeroUrl' => $normalize($v['banner_hero'] ?? null),
                    'v'                    => $v,
                ];
            } elseif ($key === 'gallery') {
                $images = $v['images'] ?? [];
                $sectionsData[$key] = [
                    'item'   => $settingItem,
                    'images' => array_map(fn($p) => ['path' => $p, 'url' => $normalize($p)], $images),
                    'v'      => $v,
                ];
            } elseif ($key === 'vision_mission') {
                if (!isset($v['items']) || !is_array($v['items'])) $v['items'] = [];
                $sectionsData[$key] = [
                    'item'            => $settingItem,
                    'currentImageUrl' => $normalize($v['image'] ?? null),
                    'v'               => $v,
                ];
            } elseif ($key === 'consultation') {
                $sectionsData[$key] = [
                    'item'            => $settingItem,
                    'currentImageUrl' => $normalize($v['image'] ?? null),
                    'v'               => $v,
                ];
            } elseif ($key === 'insurance') {
                $logos = $v['logos'] ?? [];
                $sectionsData[$key] = [
                    'item'  => $settingItem,
                    'logos' => array_map(fn($p) => ['path' => $p, 'url' => $normalize($p)], $logos),
                    'v'     => $v,
                ];
            }
        }

        return view('setting.about-page', [
            'currentSection' => $section,
            'settingType'    => $settingType,
            'settingTypes'   => $this->homeSettingTypes,
            'sectionsList'   => $sectionsList,
            'sectionsData'   => $sectionsData,
        ]);
    }

    public function updateAboutHero(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $request->validate(['banner_hero_file' => ['nullable', 'image', 'max:5120']]);
        $item = $this->firstOrCreateHomeSetting('about_page_hero', $settingType);
        $v = $item->value ?? [];
        $payload = ['banner_hero' => $v['banner_hero'] ?? ''];
        if ($request->hasFile('banner_hero_file')) {
            if (!empty($v['banner_hero']) && file_exists(public_path($v['banner_hero']))) @unlink(public_path($v['banner_hero']));
            $file = $request->file('banner_hero_file');
            $filename = 'about_hero_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['banner_hero'] = 'uploads/settings/' . $filename;
        }
        $saved = $this->updateOrCreateHomeSetting('about_page_hero', $payload, $settingType);
        return response()->json(['message' => 'Đã lưu phần đầu.', 'key' => $saved->key, 'value' => $payload]);
    }

    public function updateAboutGallery(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $request->validate(['gallery_files.*' => ['nullable', 'image', 'max:5120']]);
        $item = $this->firstOrCreateHomeSetting('about_page_gallery', $settingType);
        $v = $item->value ?? [];
        $existingImages = $v['images'] ?? [];

        // 1) Filter existing images first (keep only the ones user wants to keep)
        $keepPaths = $request->input('keep_images', []);
        if (!empty($keepPaths)) {
            $existingImages = array_values(array_filter($existingImages, fn($p) => in_array($p, $keepPaths)));
        }

        // 2) Then append newly uploaded files
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $filename = 'about_gallery_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $existingImages[] = 'uploads/settings/' . $filename;
            }
        }

        $payload = ['images' => $existingImages];
        $saved = $this->updateOrCreateHomeSetting('about_page_gallery', $payload, $settingType);
        return response()->json(['message' => 'Đã lưu thư viện ảnh.', 'key' => $saved->key, 'value' => $payload]);
    }

    public function removeAboutGalleryImage(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $request->validate(['path' => ['required', 'string']]);
        $pathToRemove = $request->input('path');

        $item = $this->firstOrCreateHomeSetting('about_page_gallery', $settingType);
        $v = $item->value ?? [];
        $images = $v['images'] ?? [];

        // Remove the path from array
        $images = array_values(array_filter($images, fn($p) => $p !== $pathToRemove));

        // Delete physical file if exists in our uploads folder (safety check)
        if (str_starts_with($pathToRemove, 'uploads/') && file_exists(public_path($pathToRemove))) {
            @unlink(public_path($pathToRemove));
        }

        $payload = ['images' => $images];
        $saved = $this->updateOrCreateHomeSetting('about_page_gallery', $payload, $settingType);
        return response()->json(['message' => 'Đã xoá ảnh khỏi thư viện.', 'key' => $saved->key, 'value' => $payload]);
    }

    public function updateAboutVisionMission(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $request->validate([
            'subtitle'            => ['nullable', 'string'],
            'title'               => ['nullable', 'string'],
            'description'         => ['nullable', 'string'],
            'vm_image'            => ['nullable', 'image', 'max:5120'],
            'items.*.icon'        => ['nullable', 'string'],
            'items.*.title'       => ['nullable', 'string'],
            'items.*.description' => ['nullable', 'string'],
        ]);
        $item = $this->firstOrCreateHomeSetting('about_page_vision_mission', $settingType);
        $v = $item->value ?? [];
        $payload = [
            'subtitle'    => $request->input('subtitle', $v['subtitle'] ?? ''),
            'title'       => $request->input('title', $v['title'] ?? ''),
            'description' => $request->input('description', $v['description'] ?? ''),
            'image'       => $v['image'] ?? '',
            'items'       => [],
        ];
        if ($request->hasFile('vm_image')) {
            if (!empty($v['image']) && file_exists(public_path($v['image']))) @unlink(public_path($v['image']));
            $file = $request->file('vm_image');
            $filename = 'about_vision_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['image'] = 'uploads/settings/' . $filename;
        }
        foreach ($request->input('items', []) as $it) {
            $payload['items'][] = ['icon' => $it['icon'] ?? '', 'title' => $it['title'] ?? '', 'description' => $it['description'] ?? ''];
        }
        $saved = $this->updateOrCreateHomeSetting('about_page_vision_mission', $payload, $settingType);
        return response()->json(['message' => 'Đã lưu tầm nhìn & sứ mệnh.', 'key' => $saved->key, 'value' => $payload]);
    }

    public function updateAboutConsultation(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $request->validate([
            'title'      => ['nullable', 'string'],
            'btn_text'   => ['nullable', 'string'],
            'btn_link'   => ['nullable', 'string'],
            'consultation_image' => ['nullable', 'image', 'max:5120'],
        ]);
        $item = $this->firstOrCreateHomeSetting('about_page_consultation', $settingType);
        $v = $item->value ?? [];
        $payload = [
            'title'    => $request->input('title', $v['title'] ?? ''),
            'btn_text' => $request->input('btn_text', $v['btn_text'] ?? ''),
            'btn_link' => $request->input('btn_link', $v['btn_link'] ?? ''),
            'image'    => $v['image'] ?? '',
        ];
        if ($request->hasFile('consultation_image')) {
            if (!empty($v['image']) && file_exists(public_path($v['image']))) @unlink(public_path($v['image']));
            $file = $request->file('consultation_image');
            $filename = 'about_consultation_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $payload['image'] = 'uploads/settings/' . $filename;
        }
        $saved = $this->updateOrCreateHomeSetting('about_page_consultation', $payload, $settingType);
        return response()->json(['message' => 'Đã lưu đặt lịch tư vấn.', 'key' => $saved->key, 'value' => $payload]);
    }

    public function updateAboutInsurance(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $request->validate([
            'title'        => ['nullable', 'string'],
            'logo_files.*' => ['nullable', 'image', 'max:5120'],
        ]);
        $item = $this->firstOrCreateHomeSetting('about_page_insurance', $settingType);
        $v = $item->value ?? [];
        $existingLogos = $v['logos'] ?? [];

        // 1) Filter existing logos first (keep only what user wants)
        $keepPaths = $request->input('keep_logos', []);
        if (!empty($keepPaths)) {
            $existingLogos = array_values(array_filter($existingLogos, fn($p) => in_array($p, $keepPaths)));
        }

        // 2) Append newly uploaded logos
        if ($request->hasFile('logo_files')) {
            foreach ($request->file('logo_files') as $file) {
                $filename = 'about_insurance_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $existingLogos[] = 'uploads/settings/' . $filename;
            }
        }

        $payload = ['title' => $request->input('title', $v['title'] ?? ''), 'logos' => $existingLogos];
        $saved = $this->updateOrCreateHomeSetting('about_page_insurance', $payload, $settingType);
        return response()->json(['message' => 'Đã lưu thông tin bảo hiểm.', 'key' => $saved->key, 'value' => $payload]);
    }

    public function removeAboutInsuranceLogo(Request $request)
    {
        $settingType = $this->resolveHomeSettingType($request->input('type'));
        $request->validate(['path' => ['required', 'string']]);
        $pathToRemove = $request->input('path');

        $item = $this->firstOrCreateHomeSetting('about_page_insurance', $settingType);
        $v = $item->value ?? [];
        $logos = $v['logos'] ?? [];
        $logos = array_values(array_filter($logos, fn($p) => $p !== $pathToRemove));

        if (str_starts_with($pathToRemove, 'uploads/') && file_exists(public_path($pathToRemove))) {
            @unlink(public_path($pathToRemove));
        }

        $payload = ['title' => $v['title'] ?? '', 'logos' => $logos];
        $saved = $this->updateOrCreateHomeSetting('about_page_insurance', $payload, $settingType);
        return response()->json(['message' => 'Đã xoá logo.', 'key' => $saved->key, 'value' => $payload]);
    }
}
