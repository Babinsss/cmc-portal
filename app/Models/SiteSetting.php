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
            'location' => 'Brgy. Lanot, Roxas City, Capiz 5800, Philippines',
            'hotline_1' => '(036) 621-0030',
            'hotline_2' => '(036) 621-0823',
            'email' => 'info@capizmedicalcenter.gov.ph',
            'dpo_email' => 'dpo@capizmedicalcenter.gov.ph',
            'facebook_url' => 'https://www.facebook.com/RMPHCapiz',
            'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Roxas+Memorial+Provincial+Hospital+Outpatient+Department,+Brgy.+Lanot,+Roxas+City,+Capiz',
            'google_maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31275.168499060495!2d122.71808591083985!3d11.523420600000009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a5f485054ad937%3A0xdfe1c6fcffcd9ce2!2sRoxas%20Memorial%20Provincial%20Hospital%20Outpatient%20Department!5e0!3m2!1sen!2sph!4v1795160414285!5m2!1sen!2sph',
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
