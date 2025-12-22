<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\About\BannerSettings;
use App\Models\City;
use App\Models\CitySectionContent;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Notifications\Notification;
use Livewire\Attributes\Url;

class AboutBannerSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $title = 'Баннер';
    protected static ?string $navigationGroup = 'О компании';
    protected static ?string $slug = 'blocks/about/banner';
    protected static string $settings = BannerSettings::class;

    #[Url]
    public ?int $selectedCityId = null;

    public function mount(): void
    {
        parent::mount();
        if (!$this->selectedCityId) {
            $this->selectedCityId = City::where('is_default', true)->first()?->id ?? City::first()?->id;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Выбор города')
                    ->schema([
                        Select::make('selectedCityId')
                            ->label('Город')
                            ->options(City::pluck('name', 'id')->map(fn($name) => is_array($name) ? ($name['ru'] ?? $name['kk'] ?? '') : $name))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedCityId = $state;
                                $this->loadCityContent();
                            })
                            ->helperText('Выберите город для настройки уникального контента'),
                    ])
                    ->collapsible(),

                Card::make('Блок №1')
                    ->schema([
                        TextInput::make('title_ru')
                            ->required()
                            ->label('Заголовок на русском'),
                        TextInput::make('title_kk')
                            ->required()
                            ->label('Заголовок на казахском'),
                        FileUpload::make('bg_image')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                ]),

                Card::make('Блок №2')
                    ->schema([
                        TextInput::make('sub_title')
                            ->required()
                            ->label('Заголовок'),
                        Textarea::make('desc_kk')
                            ->required()
                            ->label('Краткое описание на казахском'),
                        Textarea::make('desc_ru')
                            ->required()
                            ->label('Краткое описание на русском'),
                        FileUpload::make('photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),
            ]);
    }

    protected function loadCityContent(): void
    {
        if (!$this->selectedCityId) {
            $settings = app(BannerSettings::class);
            $this->form->fill([
                'title_ru' => $settings->title_ru,
                'title_kk' => $settings->title_kk,
                'sub_title' => $settings->sub_title,
                'desc_ru' => $settings->desc_ru,
                'desc_kk' => $settings->desc_kk,
                'photo' => $settings->photo,
                'bg_image' => $settings->bg_image,
                'selectedCityId' => null,
            ]);
            return;
        }

        $content = CitySectionContent::getContent($this->selectedCityId, 'about_banner');
        if ($content) {
            $content['selectedCityId'] = $this->selectedCityId;
            $this->form->fill($content);
        } else {
            $settings = app(BannerSettings::class);
            $this->form->fill([
                'title_ru' => $settings->title_ru,
                'title_kk' => $settings->title_kk,
                'sub_title' => $settings->sub_title,
                'desc_ru' => $settings->desc_ru,
                'desc_kk' => $settings->desc_kk,
                'photo' => $settings->photo,
                'bg_image' => $settings->bg_image,
                'selectedCityId' => $this->selectedCityId,
            ]);
        }
    }

    public function save(): void
    {
        $data = $this->form->getState();
        unset($data['selectedCityId']);

        if ($this->selectedCityId) {
            CitySectionContent::setContent($this->selectedCityId, 'about_banner', $data);
            Notification::make()
                ->title('Сохранено')
                ->body('Контент для города успешно сохранён')
                ->success()
                ->send();
        } else {
            parent::save();
        }
    }
}
