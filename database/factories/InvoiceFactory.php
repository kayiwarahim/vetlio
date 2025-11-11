<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Organisation;
use App\Models\PriceList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $invoiceDate = Carbon::now()->subDays(rand(0, 30));
        $dueDate = (clone $invoiceDate)->addDays(rand(3, 14));

        $base = fake()->numberBetween(50, 500);
        $discount = fake()->boolean(30) ? rand(0, 20) : 0;
        $taxRate = fake()->randomElement([13, 25]);
        $baseTotal = $base - ($base * $discount / 100);
        $taxAmount = round($baseTotal * $taxRate / 100);
        $grandTotal = round($baseTotal + $taxAmount);

        return [
            'uuid' => (string) Str::uuid(),
            'code' => 'INV-' . strtoupper(Str::random(6)),
            'client_id' => Client::factory(),
            'user_id' => User::factory(), // created by
            'issuer_id' => User::factory(), // signed by / responsible vet
            'branch_id' => Branch::factory(),
            'price_list_id' => PriceList::factory(),
            'invoice_date' => $invoiceDate,
            'invoice_due_date' => $dueDate,
            'storno_of_id' => null,
            'storno_user_id' => null,
            'client_note' => fake()->optional()->sentence(),
            'terms_and_conditions' => fake()->optional()->paragraph(2),
            'payment_method_id' => fake()->randomElement([1, 2, 3]), // e.g. 1=cash, 2=card, 3=transfer
            'card_id' => null,
            'bank_account_id' => null,

            'total_base_price' => $baseTotal,
            'total_discount' => $discount,
            'total_tax' => $taxAmount,
            'total' => $grandTotal,

            'zki' => fake()->boolean(30) ? strtoupper(Str::random(16)) : null,
            'jir' => fake()->boolean(30) ? strtoupper(Str::random(20)) : null,
            'qrcode' => fake()->boolean(20) ? fake()->regexify('[A-Z0-9]{200}') : null,
            'fiscalization_at' => fake()->boolean(40) ? Carbon::now()->subDays(rand(0, 5)) : null,
            'organisation_id' => Organisation::factory(),
        ];
    }

    /**
     * State: demo invoice with predictable data
     */
    public function demo(): static
    {
        return $this->state(fn () => [
            'code' => 'INV-DEMO-001',
            'payment_method_id' => 1,
            'total' => 120,
            'total_tax' => 20,
            'total_base_price' => 100,
            'total_discount' => 0,
            'invoice_date' => now()->subDays(2),
            'invoice_due_date' => now()->addDays(5),
        ]);
    }

    /**
     * After creating: generate random invoice items
     */
    public function configure(): static
    {
        return $this->afterCreating(function (\App\Models\Invoice $invoice) {
            \App\Models\InvoiceItem::factory()
                ->count(rand(2, 5))
                ->for($invoice)
                ->for($invoice->organisation)
                ->create();

            $invoice->load('invoiceItems');

            $totals = $invoice->invoiceItems->reduce(function ($carry, $item) {
                $carry['base'] += $item->base_price;
                $carry['tax'] += $item->tax;
                $carry['total'] += $item->total;
                return $carry;
            }, ['base' => 0, 'tax' => 0, 'total' => 0]);

            $invoice->update([
                'total_base_price' => $totals['base'],
                'total_tax' => $totals['tax'],
                'total' => $totals['total'],
            ]);
        });
    }
}
