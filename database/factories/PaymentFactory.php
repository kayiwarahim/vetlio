<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Organisation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $date = Carbon::now()->subDays(rand(0, 60));
        $amount = fake()->randomFloat(2, 20, 300);

        return [
            'uuid' => (string)Str::uuid(),
            'code' => 'PAY-' . strtoupper(Str::random(6)),
            'branch_id' => Branch::factory(),
            'invoice_id' => null,
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'payment_method_id' => fake()->randomElement([1, 2, 3]), // 1=cash, 2=card, 3=bank
            'transaction_id' => fake()->optional(40)->bothify('TX-#####'),
            'note' => fake()->optional(40)->sentence(),
            'payment_at' => $date,
            'amount' => $amount,
            'organisation_id' => Organisation::factory(),
        ];
    }

    /**
     * State: povezano s računom
     */
    public function forInvoice(Invoice $invoice): static
    {
        return $this->state(fn() => [
            'invoice_id' => $invoice->id,
            'client_id' => $invoice->client_id,
            'branch_id' => $invoice->branch_id,
            'user_id' => $invoice->user_id,
            'organisation_id' => $invoice->organisation_id,
            'amount' => fake()->randomFloat(2, $invoice->total * 0.3, $invoice->total),
            'payment_at' => now()->subDays(rand(0, 5)),
        ]);
    }

    /**
     * State: demo predictable payment
     */
    public function demo(): static
    {
        return $this->state(fn() => [
            'code' => 'PAY-DEMO-001',
            'payment_method_id' => 1,
            'amount' => 100.00,
            'payment_at' => now()->subDay(),
        ]);
    }
}
