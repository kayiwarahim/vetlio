<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Organisation;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $qty = rand(1, 3);
        $price = fake()->randomFloat(2, 10, 150);
        $discount = fake()->boolean(25) ? rand(5, 20) : 0;
        $basePrice = $price * $qty;
        $taxRate = fake()->randomElement([13, 25]);
        $discountValue = round($basePrice * $discount / 100, 2);
        $tax = round(($basePrice - $discountValue) * $taxRate / 100, 2);
        $total = round($basePrice - $discountValue + $tax, 2);

        return [
            'organisation_id' => Organisation::factory(),
            'invoice_id' => Invoice::factory(),
            'priceable_id' => Service::factory(),
            'priceable_type' => Service::class,
            'name' => fake()->randomElement([
                'Vaccination',
                'Checkup',
                'Dental Cleaning',
                'Surgery Consultation',
                'Blood Test',
            ]),
            'description' => fake()->optional()->sentence(),
            'quantity' => $qty,
            'price' => $price,
            'base_price' => $basePrice,
            'discount' => $discountValue,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    /**
     * Demo predictable item
     */
    public function demo(): static
    {
        return $this->state(fn() => [
            'name' => 'General Checkup',
            'quantity' => 1,
            'price' => 50.00,
            'base_price' => 50.00,
            'discount' => 0,
            'tax' => 12.50,
            'total' => 62.50,
        ]);
    }
}
