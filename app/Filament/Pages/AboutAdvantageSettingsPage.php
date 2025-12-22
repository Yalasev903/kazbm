<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\About\AdvantageSettings;
use App\Models\City;
use App\Models\CitySectionContent;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Notifications\Notification;
use Livewire\Attributes\Url;

class AboutAdvantageSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $title = 'Преимущества';

    protected static ?string $navigationGroup = 'О компании';

    protected static ?string $slug = 'blocks/about/advantage';

    protected static string $settings = AdvantageSettings::class;

    #[Url]
    public ?int $selectedCityId = null;

    public function mount(): void
    {
        parent::mount();
        if (!$this->selectedCityId) {
            $this->selectedCityId = City::where('is_default', true)->first()?->id ?? City::first()?->id;
        }
        $this->loadCityContent();
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
                    Repeater::make('items')
                        ->maxItems(4)
                        ->label('Преимущества')
                        ->collapsed()
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->label('Заголовок'),
                            Textarea::make('desc')
                                ->required()
                                ->label('Описание'),
                            FileUpload::make('image')
                                ->directory('advantage')
                                ->required()
                                ->image()
                                ->label('Фото')
                        ])
                ]),
            ]);
    }

    protected function loadCityContent(): void
    {
        if (!$this->selectedCityId) {
            $settings = app(AdvantageSettings::class);
            $this->form->fill([
                'items' => $settings->items,
                'selectedCityId' => null,
            ]);
            return;
        }

        $content = CitySectionContent::getContent($this->selectedCityId, 'about_advantage');
        if ($content) {
            $content['selectedCityId'] = $this->selectedCityId;
            $this->form->fill($content);
        } else {
            $settings = app(AdvantageSettings::class);
            $this->form->fill([
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
            CitySectionContent::setContent($this->selectedCityId, 'about_advantage', $data);
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
