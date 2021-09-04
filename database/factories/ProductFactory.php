<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $path = public_path('uploads/images/products');

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        return [
            'title' => $this->faker->text(50),
            'description' => $this->faker->paragraph(rand(2, 3)),
            'price' => $price = $this->faker->randomFloat(2, 10, 100),
            'qty' => rand(15, 100),
            'thumbnail' => $this->faker->image('public/uploads/images/products', 600, 600, 'Product: ', false),
        ];
    }
}
