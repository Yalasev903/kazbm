<?php

namespace App\Repositories;

use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

class SettingsRepository extends DatabaseSettingsRepository
{

    private static $items = [];

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->setItems();
    }

    public function getPropertiesInGroup(string $group): array
    {
        return self::$items[$group] ?? [];
    }

    public function checkIfPropertyExists(string $group, string $name): bool
    {
        $properties = $this->getPropertiesInGroup($group);
        return array_key_exists($name, $properties);
    }

    private function setItems(): void
    {
        if (empty(self::$items)) {
            $items = [];
            $this->getBuilder()
                ->get(['name', 'payload', 'group'])
                ->mapWithKeys(function (object $object) use (&$items) {
                    $data = [$object->name => json_decode($object->payload, true)];
                    $items[$object->group] = array_merge($items[$object->group] ?? [], $data);
                    return $data;
                })
                ->toArray();
            self::$items = $items;
        }
    }

}
