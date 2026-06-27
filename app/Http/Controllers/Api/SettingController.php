<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    private array $publicDefaultKeys = [
        'site',
        'site_assets_travel',
        'topbar_info_travel',
        'floating_info_travel',
    ];

    private array $typedHomeKeys = [
        'utilities_home',
        'stats_home',
        'services_home',
        'how_it_work_home',
        'faq_home',
        'awards_home',
        'contact_home',
        'floating_info',
        'service_hero',
        'service_features',
        'service_plans',
        'contact_page_hero',
        'contact_page_info',
        'contact_page_locations',
        'about_page_hero',
        'about_page_gallery',
        'about_page_intro',
        'about_page_values',
        'about_page_vision_mission',
        'about_page_consultation',
        'blog_page',
        'shop_page',
        'faq_page',
    ];

    public function index(Request $request, $domain = null)
    {
        try {
            $keys = $request->input('keys');
            $keys = $keys
                ? array_values(array_filter(array_map('trim', explode(',', $keys))))
                : $this->publicDefaultKeys;
            $settingType = $this->resolveSettingTypeFromHost($request);
            $queryKeys = $keys;

            if ($keys && $settingType) {
                $queryKeys = array_values(array_unique(array_merge(
                    $keys,
                    array_map(function ($key) use ($settingType) {
                        return in_array($key, $this->typedHomeKeys, true)
                            ? $key . '_' . $settingType
                            : $key;
                    }, $keys)
                )));
            }

            $q = Setting::query()->select(['key', 'value']);
            if ($queryKeys && count($queryKeys)) {
                $q->whereIn('key', $queryKeys);
            }

            $rows = $q->get();

            $data = $rows->mapWithKeys(function ($s) {
                return [$s->key => $s->value];
            })->all();

            $data = $this->aliasTypedHomeSettings($data, $settingType, $keys);

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data'    => $data,
                'meta'    => ['empty' => empty($data)],
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Setting index error', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => __('messages.server_error'),
                'data'    => [],
            ], 500);
        }
    }

    private function resolveSettingTypeFromHost(Request $request): ?string
    {
        return 'travel';
    }

    private function aliasTypedHomeSettings(array $data, ?string $type, ?array $requestedKeys = null): array
    {
        if (!$type) {
            return $requestedKeys ? array_intersect_key($data, array_flip($requestedKeys)) : $data;
        }

        if ($requestedKeys) {
            $response = [];

            foreach ($requestedKeys as $key) {
                $typedKey = in_array($key, $this->typedHomeKeys, true) ? $key . '_' . $type : $key;

                if (array_key_exists($typedKey, $data)) {
                    $response[$key] = $data[$typedKey];
                } elseif (array_key_exists($key, $data)) {
                    $response[$key] = $data[$key];
                }
            }

            return $response;
        }

        foreach ($this->typedHomeKeys as $baseKey) {
            $typedKey = $baseKey . '_' . $type;

            if (array_key_exists($typedKey, $data)) {
                $data[$baseKey] = $data[$typedKey];
            }
        }

        return $data;
    }
}
