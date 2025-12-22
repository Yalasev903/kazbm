<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\OurProductSettings;
use App\Filament\Traits\HasCityContent;
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
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Livewire\Attributes\Url;

class OurProductSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $title = 'Наша продукция';
    protected static ?string $slug = 'blocks/our-products';
    protected static string $settings = OurProductSettings::class;

    #[Url]
    public ?int $selectedCityId = null;

    public function mount(): void
    {
        parent::mount();

        if (!$this->selectedCityId) {
            $this->selectedCityId = City::where('is_default', true)->first()?->id
                ?? City::first()?->id;
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
                            ->helperText('Выберите город для настройки уникального контента. Оставьте пустым для редактирования базовых настроек.'),
                    ])
                    ->collapsible(),

                Card::make('Блок №1 - Баннер')
                    ->schema([
                        Textarea::make('hero_desc')
                            ->required()
                            ->label('Краткое описание'),
                        FileUpload::make('hero_image')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),

                Card::make('Блок №2 - Особенности')
                    ->schema([
                        TextInput::make('feature_title')
                            ->required()
                            ->label('Заголовок'),
                        TinyEditor::make('feature_desc')
                            ->fileAttachmentsDirectory('uploads/settings')
                            ->label('Описание'),
                        FileUpload::make('feature_photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),

                Card::make('Блок №3 - Причины')
                    ->schema([
                        TextInput::make('reason_title')
                            ->required()
                            ->label('Заголовок'),
                        TinyEditor::make('reason_desc')
                            ->fileAttachmentsDirectory('uploads/settings'),
                    ]),

                Card::make('Блок №4 - Выводы')
                    ->schema([
                        Textarea::make('conclusion_text')
                            ->required()
                            ->label('Описание'),
                    ]),
            ]);
    }

    protected function loadCityContent(): void
    {
        if (!$this->selectedCityId) {
            $settings = app(OurProductSettings::class);
            $this->form->fill([
                'hero_desc' => $settings->hero_desc,
                'hero_image' => $settings->hero_image,
                'feature_title' => $settings->feature_title,
                'feature_desc' => $settings->feature_desc,
                'feature_photo' => $settings->feature_photo,
                'reason_title' => $settings->reason_title,
                'reason_desc' => $settings->reason_desc,
                'conclusion_text' => $settings->conclusion_text,
                'selectedCityId' => null,
            ]);
            return;
        }

        $content = CitySectionContent::getContent($this->selectedCityId, 'our_product');
        if ($content) {
            $content['selectedCityId'] = $this->selectedCityId;
            $this->form->fill($content);
        } else {
            $settings = app(OurProductSettings::class);
            $this->form->fill([
                'hero_desc' => $settings->hero_desc,
                'hero_image' => $settings->hero_image,
                'feature_title' => $settings->feature_title,
                'feature_desc' => $settings->feature_desc,
                'feature_photo' => $settings->feature_photo,
                'reason_title' => $settings->reason_title,
                'reason_desc' => $settings->reason_desc,
                'conclusion_text' => $settings->conclusion_text,
                'selectedCityId' => $this->selectedCityId,
            ]);
        }
    }

    public function save(): void
    {
        $data = $this->form->getState();
        unset($data['selectedCityId']);

        if ($this->selectedCityId) {
            CitySectionContent::setContent($this->selectedCityId, 'our_product', $data);
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
