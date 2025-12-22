<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\OblicAboutProductSettings;
use App\Models\City;
use App\Models\CitySectionContent;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Notifications\Notification;
use Livewire\Attributes\Url;

class OblicAboutProductSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $title = 'Продукция';
    protected static ?string $navigationGroup = 'О компании (облицовочный кирпич)';
    protected static ?string $slug = 'blocks/oblic-company/product';
    protected static string $settings = OblicAboutProductSettings::class;

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
                    TextInput::make('title')
                        ->required()
                        ->label('Заголовок'),
                    Textarea::make('description')
                        ->required()
                        ->label('Описание'),
                    FileUpload::make('photo')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Фото'),
                    FileUpload::make('item_photo')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Дополнительное фото'),
                    Repeater::make('items')
                        ->label('Элементы')
                        ->collapsed()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->label('Название'),
                            Textarea::make('desc')
                                ->required()
                                ->label('Описание'),
                        ])
                ]),
            ]);
    }

    protected function loadCityContent(): void
    {
        if (!$this->selectedCityId) {
            $settings = app(OblicAboutProductSettings::class);
            $this->form->fill([
                'title' => $settings->title,
                'description' => $settings->description,
                'photo' => $settings->photo,
                'item_photo' => $settings->item_photo,
                'items' => $settings->items,
                'selectedCityId' => null,
            ]);
            return;
        }

        $content = CitySectionContent::getContent($this->selectedCityId, 'oblic_about_product');
        if ($content) {
            $content['selectedCityId'] = $this->selectedCityId;
            $this->form->fill($content);
        } else {
            $settings = app(OblicAboutProductSettings::class);
            $this->form->fill([
                'title' => $settings->title,
                'description' => $settings->description,
                'photo' => $settings->photo,
                'item_photo' => $settings->item_photo,
                'items' => $settings->items,
                'selectedCityId' => $this->selectedCityId,
            ]);
        }
    }

    public function save(): void
    {
        $data = $this->form->getState();
        unset($data['selectedCityId']);

        if ($this->selectedCityId) {
            CitySectionContent::setContent($this->selectedCityId, 'oblic_about_product', $data);
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
