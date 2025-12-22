<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\About\ProductSettings;
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

class AboutProductSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $title = 'Продукция';
    protected static ?string $navigationGroup = 'О компании';
    protected static ?string $slug = 'blocks/about/product';
    protected static string $settings = ProductSettings::class;

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
                        TextInput::make('title')
                            ->required()
                            ->label('Заголовок'),
                        Textarea::make('description')
                            ->required()
                            ->label('Описание'),
                        FileUpload::make('photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),

                Card::make('Блок №2')
                    ->schema([
                        FileUpload::make('item_photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                        Repeater::make('items')
                            ->maxItems(2)
                            ->label('Элементы')
                            ->collapsed()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Наименование'),
                                Textarea::make('desc')
                                    ->required()
                                    ->label('Краткое описание'),
                            ])
                    ]),
            ]);
    }

    protected function loadCityContent(): void
    {
        if (!$this->selectedCityId) {
            $settings = app(ProductSettings::class);
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

        $content = CitySectionContent::getContent($this->selectedCityId, 'about_product');
        if ($content) {
            $content['selectedCityId'] = $this->selectedCityId;
            $this->form->fill($content);
        } else {
            $settings = app(ProductSettings::class);
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
            CitySectionContent::setContent($this->selectedCityId, 'about_product', $data);
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
