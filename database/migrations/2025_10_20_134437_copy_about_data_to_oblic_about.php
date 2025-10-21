<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Копируем данные из about_banner в oblic_about_banner
        $bannerFields = ['title_ru', 'title_kk', 'photo', 'sub_title', 'desc_ru', 'desc_kk', 'bg_image'];

        foreach ($bannerFields as $field) {
            $data = DB::table('settings')
                ->where('group', 'about_banner')
                ->where('name', $field)
                ->first();

            if ($data) {
                DB::table('settings')->updateOrInsert(
                    [
                        'group' => 'oblic_about_banner',
                        'name' => $field
                    ],
                    [
                        'locked' => $data->locked,
                        'payload' => $data->payload,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // Копируем данные из about_product в oblic_about_product
        $productFields = ['title', 'description', 'photo', 'item_photo', 'items'];

        foreach ($productFields as $field) {
            $data = DB::table('settings')
                ->where('group', 'about_product')
                ->where('name', $field)
                ->first();

            if ($data) {
                DB::table('settings')->updateOrInsert(
                    [
                        'group' => 'oblic_about_product',
                        'name' => $field
                    ],
                    [
                        'locked' => $data->locked,
                        'payload' => $data->payload,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down()
    {
        DB::table('settings')
            ->where('group', 'oblic_about_banner')
            ->delete();

        DB::table('settings')
            ->where('group', 'oblic_about_product')
            ->delete();
    }
};
