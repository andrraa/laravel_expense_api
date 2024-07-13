<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expense_types')->insert(
            [
                ['name' => 'Income',
                    'is_active' => '1',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Outcome',
                    'is_active' => '1',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
    }
}
