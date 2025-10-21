<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Копируем данные из advantage в oblic_advantage с преобразованием структуры
        $advantageData = DB::table('settings')
            ->where('group', 'advantage')
            ->where('name', 'items')
            ->first();

        if ($advantageData) {
            $oldItems = json_decode($advantageData->payload, true);

            // Преобразуем структуру данных
            $newItems = [];
            foreach ($oldItems as $item) {
                $newItems[] = [
                    'title_ru' => $item['title'] ?? '',
                    'title_kk' => $item['title'] ?? '', // Пока копируем тот же текст
                    'desc_ru' => $item['desc'] ?? '',
                    'desc_kk' => $item['desc'] ?? '', // Пока копируем тот же текст
                    'image' => $item['image'] ?? '',
                    'small_image' => $item['small_image'] ?? '',
                ];
            }

            DB::table('settings')->updateOrInsert(
                [
                    'group' => 'oblic_advantage',
                    'name' => 'items'
                ],
                [
                    'locked' => $advantageData->locked,
                    'payload' => json_encode($newItems),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down()
    {
        DB::table('settings')
            ->where('group', 'oblic_advantage')
            ->where('name', 'items')
            ->delete();
    }
};
