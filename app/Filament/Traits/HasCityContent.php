<?php

namespace App\Filament\Traits;

use App\Models\City;
use App\Models\CitySectionContent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Livewire\Attributes\Url;

trait HasCityContent
{
    #[Url]
    public ?int $selectedCityId = null;

    abstract protected function getSectionKey(): string;

    public function bootHasCityContent(): void
    {
        if (!$this->selectedCityId) {
            $this->selectedCityId = City::where('is_default', true)->first()?->id
                ?? City::first()?->id;
        }
    }

    public function mountHasCityContent(): void
    {
        $this->bootHasCityContent();
        $this->loadCityContent();
    }

    protected function getCitySelectComponent(): Section
    {
        return Section::make('Выбор города')
            ->schema([
                Select::make('selectedCityId')
                    ->label('Город')
                    ->options(City::pluck('name', 'id')->map(fn($name) => is_array($name) ? ($name['ru'] ?? $name['kk'] ?? '') : $name))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn() => $this->loadCityContent())
                    ->helperText('Выберите город для настройки уникального контента'),
            ])
            ->collapsible();
    }

    protected function loadCityContent(): void
    {
        if (!$this->selectedCityId) {
            return;
        }

        $content = CitySectionContent::getContent($this->selectedCityId, $this->getSectionKey());
        if ($content) {
            $this->form->fill($content);
        }
    }

    public function saveCityContent(array $data): void
    {
        if (!$this->selectedCityId) {
            Notification::make()
                ->title('Ошибка')
                ->body('Выберите город')
                ->danger()
                ->send();
            return;
        }

        CitySectionContent::setContent($this->selectedCityId, $this->getSectionKey(), $data);

        Notification::make()
            ->title('Сохранено')
            ->body('Контент для города успешно сохранён')
            ->success()
            ->send();
    }

    protected function getSelectedCityName(): string
    {
        if (!$this->selectedCityId) {
            return '';
        }

        $city = City::find($this->selectedCityId);
        if (!$city) {
            return '';
        }

        $name = $city->name;
        return is_array($name) ? ($name['ru'] ?? $name['kk'] ?? '') : $name;
    }
}
