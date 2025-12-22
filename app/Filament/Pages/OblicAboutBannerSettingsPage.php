<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\OblicAboutBannerSettings;
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

class OblicAboutBannerSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $title = 'Баннер';
    protected static ?string $navigationGroup = 'О компании (облицовочный кирпич)';
    protected static ?string $slug = 'blocks/oblic-company/banner';
    protected static string $settings = OblicAboutBannerSettings::class;

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

                Card::make([
                    TextInput::make('title_ru')
                        ->required()
                        ->label('Заголовок на русском'),
                    TextInput::make('title_kk')
                        ->required()
                        ->label('Заголовок на казахском'),
                    FileUpload::make('photo')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Фото'),
                    TextInput::make('sub_title')
                        ->required()
                        ->label('Подзаголовок'),
                    Textarea::make('desc_ru')
                        ->required()
                        ->label('Описание на русском'),
                    Textarea::make('desc_kk')
                        ->required()
                        ->label('Описание на казахском'),
                    FileUpload::make('bg_image')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Фоновое изображение'),
                ]),
            ]);
    }

    protected function loadCityContent(): void
    {
        if (!$this->selectedCityId) {
            $settings = app(OblicAboutBannerSettings::class);
            $this->form->fill([
                'title_ru' => $settings->title_ru,
                'title_kk' => $settings->title_kk,
                'photo' => $settings->photo,
                'sub_title' => $settings->sub_title,
                'desc_ru' => $settings->desc_ru,
                'desc_kk' => $settings->desc_kk,
                'bg_image' => $settings->bg_image,
                'selectedCityId' => null,
            ]);
            return;
        }

        $content = CitySectionContent::getContent($this->selectedCityId, 'oblic_about_banner');
        if ($content) {
            $content['selectedCityId'] = $this->selectedCityId;
            $this->form->fill($content);
        } else {
            $settings = app(OblicAboutBannerSettings::class);
            $this->form->fill([
                'title_ru' => $settings->title_ru,
                'title_kk' => $settings->title_kk,
                'photo' => $settings->photo,
                'sub_title' => $settings->sub_title,
                'desc_ru' => $settings->desc_ru,
                'desc_kk' => $settings->desc_kk,
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
            CitySectionContent::setContent($this->selectedCityId, 'oblic_about_banner', $data);
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
