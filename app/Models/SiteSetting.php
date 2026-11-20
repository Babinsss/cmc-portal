<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    public const CACHE_KEY = 'cmc_site_settings_map';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function defaults(): array
    {
        return [
            'hospital_name' => 'Capiz Medical Center',
            'former_name' => '',
            'tagline' => 'Pag-atipan nga may gugma',
            'hospital_classification' => 'Level II Government Hospital',
            'authorized_beds' => '147',
            'location' => 'Sitio Nasagud, Brgy. Lanot, Roxas City, Capiz 5800, Philippines',
            'hotline_1' => '(036) 621-0030',
            'hotline_2' => '(036) 621-0823',
            'email' => 'info@capizmedicalcenter.gov.ph',
            'dpo_email' => 'dpo@capizmedicalcenter.gov.ph',
            'facebook_url' => 'https://www.facebook.com/RMPHCapiz',
            'google_maps_url' => 'https://maps.google.com/?q=Capiz+Medical+Center+Sitio+Nasagud+Brgy+Lanot+Roxas+City+Capiz',
            'npc_registration_no' => 'DPO/DPS Registered — RA 10173 Compliant',
            'npc_seal_path' => 'images/npc-seal-official.jpg',
            'hero_image_path' => 'images/cmc-building.jpg',
            'campus_map_path' => 'images/cmc-campus-map.svg',
            'copyright_year' => date('Y'),
            'vision' => 'A beacon of hope and healing through accessible, comprehensive, specialized, and compassionate patient-centered public healthcare.',
            'mission' => 'We commit to delivering quality, accessible, comprehensive, and specialized healthcare through compassionate patient-centered care, excellent facilities, efficient processes, and empowered healthcare professionals to promote better health outcomes.',
            'goals' => json_encode([
                'Ensure accessible and equitable healthcare services',
                'Enhance comprehensive and specialized healthcare services',
                'Promote compassionate and patient-centered care',
                'Strengthen healthcare quality and operational excellence',
                'Develop competent, empowered, and service-oriented healthcare professionals',
                'Improve health outcomes and community well-being',
            ], JSON_UNESCAPED_UNICODE),
        ];
    }

    public static function allMap(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            $stored = self::query()->pluck('value', 'key')->all();

            return array_merge(self::defaults(), $stored);
        });
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $map = self::allMap();

        return $map[$key] ?? $default;
    }

    public static function setValue(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value],
        );

        Cache::forget(self::CACHE_KEY);
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function resolveAssetUrl(string $key, string $fallback): string
    {
        $path = trim((string) self::getValue($key, $fallback));

        if ($path === '') {
            $path = $fallback;
        }

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
