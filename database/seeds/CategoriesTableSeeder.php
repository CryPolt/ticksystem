<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $categories = [
            "двойная оплата",
            "разные способы оплаты",
            "Проблема с QR",
            "другое",
            "возврат",
            "отказ",
            "СБ",
            "Проблема с МП"
        ];


        foreach($categories as $category)
        {
            Category::create([
                'name'  => $category,
                'color' => $faker->hexcolor
            ]);
        }
    }
}
