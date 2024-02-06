<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Result;
use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::factory()->count(100)->create()->each(function ($member) {
            // Для каждого участника создаем по 100 результатов
            Result::factory()->count(100)->create([
                'member_id' => $member->id
            ]);
        });
    }
}
