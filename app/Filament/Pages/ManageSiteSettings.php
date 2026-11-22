<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System & Administration';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Site Settings & NPC Seal';

    protected static ?string $title = 'Hospital Site Settings & NPC Seal';

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null && $user->isSuperAdmin();
    }

    public function mount(): void
    {
        $map = SiteSetting::allMap();
        $goals = json_decode((string) ($map['goals'] ?? '[]'), true);
        if (! is_array($goals)) {
            $goals = [];
        }

        $formState = [
            'hospital_name' => $map['hospital_name'] ?? '',
            'former_name' => $map['former_name'] ?? '',
            'tagline' => $map['tagline'] ?? '',
            'hospital_classification' => $map['hospital_classification'] ?? '',
            'authorized_beds' => $map['authorized_beds'] ?? '147',
            'location' => $map['location'] ?? '',
            'hotline_1' => $map['hotline_1'] ?? '',
            'hotline_2' => $map['hotline_2'] ?? '',
            'email' => $map['email'] ?? '',
            'dpo_email' => $map['dpo_email'] ?? '',
            'facebook_url' => $map['facebook_url'] ?? '',
            'google_maps_url' => $map['google_maps_url'] ?? '',
            'google_maps_embed_url' => $map['google_maps_embed_url'] ?? '',
            'npc_registration_no' => $map['npc_registration_no'] ?? '',
            'copyright_year' => $map['copyright_year'] ?? date('Y'),
            'vision' => $map['vision'] ?? '',
            'mission' => $map['mission'] ?? '',
            'goals_list' => array_map(fn ($g) => ['goal' => $g], $goals),
            'npc_seal_upload' => str_starts_with((string) ($map['npc_seal_path'] ?? ''), 'images/') ? null : $map['npc_seal_path'],
            'hero_image_upload' => str_starts_with((string) ($map['hero_image_path'] ?? ''), 'images/') ? null : $map['hero_image_path'],
            'campus_map_upload' => str_starts_with((string) ($map['campus_map_path'] ?? ''), 'images/') ? null : $map['campus_map_path'],
        ];

        $this->form->fill($formState);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Hospital Identity & Capacity')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('hospital_name')->required()->maxLength(160),
                        Forms\Components\TextInput::make('former_name')->maxLength(160),
                        Forms\Components\TextInput::make('tagline')->required()->maxLength(160),
                        Forms\Components\TextInput::make('hospital_classification')->required()->maxLength(160),
                        Forms\Components\TextInput::make('authorized_beds')->numeric()->required(),
                        Forms\Components\TextInput::make('copyright_year')->numeric()->required(),
                        Forms\Components\TextInput::make('location')->required()->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Hotlines & Digital Channels')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('hotline_1')->required()->maxLength(60),
                        Forms\Components\TextInput::make('hotline_2')->required()->maxLength(60),
                        Forms\Components\TextInput::make('email')->email()->maxLength(160),
                        Forms\Components\TextInput::make('dpo_email')->label('DPO Email (RA 10173)')->email()->maxLength(160),
                        Forms\Components\TextInput::make('facebook_url')->url()->maxLength(255),
                        Forms\Components\TextInput::make('google_maps_url')->url()->maxLength(255),
                        Forms\Components\TextInput::make('google_maps_embed_url')
                            ->label('Google Maps Embed URL')
                            ->url()
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('NPC DPO/DPS Seal & Local Visual Assets')
                    ->description('Upload custom PNG/JPG/WebP/SVG images stored under local storage. Leave empty to use built-in offline defaults.')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('npc_registration_no')
                            ->label('NPC Registration Label')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('npc_seal_upload')
                            ->label('NPC DPO/DPS Seal Image')
                            ->disk('public')
                            ->directory('site-assets')
                            ->image()
                            ->maxSize(2048),
                        Forms\Components\FileUpload::make('hero_image_upload')
                            ->label('Hospital Facade Hero Photo')
                            ->disk('public')
                            ->directory('site-assets')
                            ->image()
                            ->maxSize(4096),
                        Forms\Components\FileUpload::make('campus_map_upload')
                            ->label('Offline Campus Map Image')
                            ->disk('public')
                            ->directory('site-assets')
                            ->image()
                            ->maxSize(4096),
                    ]),

                Forms\Components\Section::make('Vision, Mission & Institutional Goals')
                    ->schema([
                        Forms\Components\Textarea::make('vision')->rows(3)->required(),
                        Forms\Components\Textarea::make('mission')->rows(4)->required(),
                        Forms\Components\Repeater::make('goals_list')
                            ->label('Goals')
                            ->schema([
                                Forms\Components\Textarea::make('goal')->rows(2)->required(),
                            ])
                            ->addActionLabel('Add Goal'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $scalarKeys = [
            'hospital_name',
            'former_name',
            'tagline',
            'hospital_classification',
            'authorized_beds',
            'location',
            'hotline_1',
            'hotline_2',
            'email',
            'dpo_email',
            'facebook_url',
            'google_maps_url',
            'google_maps_embed_url',
            'npc_registration_no',
            'copyright_year',
            'vision',
            'mission',
        ];

        foreach ($scalarKeys as $key) {
            SiteSetting::setValue($key, $state[$key] ?? '');
        }

        $goals = collect($state['goals_list'] ?? [])
            ->pluck('goal')
            ->filter(fn ($g) => trim((string) $g) !== '')
            ->values()
            ->all();

        SiteSetting::setValue('goals', $goals);

        SiteSetting::setValue(
            'npc_seal_path',
            ! empty($state['npc_seal_upload']) ? $state['npc_seal_upload'] : 'images/npc-seal.svg',
        );
        SiteSetting::setValue(
            'hero_image_path',
            ! empty($state['hero_image_upload']) ? $state['hero_image_upload'] : 'images/cmc-building.jpg',
        );
        SiteSetting::setValue(
            'campus_map_path',
            ! empty($state['campus_map_upload']) ? $state['campus_map_upload'] : 'images/cmc-campus-map.svg',
        );

        Notification::make()
            ->title('Site settings updated')
            ->body('Changes are immediately live across the portal.')
            ->success()
            ->send();
    }
}
