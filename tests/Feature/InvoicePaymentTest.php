<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->assignRole('super_admin');
    }

    // ── Invoice Tests ──

    public function test_invoices_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.invoices.index'));
        $response->assertStatus(200);
    }

    public function test_invoice_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.invoices.create'));
        $response->assertStatus(200);
    }

    public function test_new_invoice_can_be_created(): void
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.invoices.store'), [
            'member_id' => $member->id,
            'notes' => 'Monthly membership fee',
            'due_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'discount_amount' => 0,
            'items' => [
                ['description' => 'Monthly Basic', 'quantity' => 1, 'unit_price' => 350000],
            ],
        ]);

        $response->assertRedirect(route('admin.invoices.index'));
        $this->assertDatabaseHas('invoices', [
            'member_id' => $member->id,
            'total_amount' => 350000,
            'status' => 'draft',
        ]);
    }

    public function test_invoice_items_total_is_calculated(): void
    {
        $member = Member::factory()->create();

        $this->actingAs($this->admin)->post(route('admin.invoices.store'), [
            'member_id' => $member->id,
            'items' => [
                ['description' => 'Item 1', 'quantity' => 2, 'unit_price' => 100000],
                ['description' => 'Item 2', 'quantity' => 1, 'unit_price' => 50000],
            ],
        ]);

        $invoice = Invoice::where('member_id', $member->id)->first();
        $this->assertEquals(250000, (float) $invoice->total_amount);
        $this->assertCount(2, $invoice->items);
    }

    public function test_invoice_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.invoices.store'), []);
        $response->assertSessionHasErrors(['member_id', 'items']);
    }

    public function test_invoice_show_page_is_displayed(): void
    {
        $invoice = Invoice::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.invoices.show', $invoice));
        $response->assertStatus(200);
    }

    public function test_invoices_can_be_filtered_by_status(): void
    {
        Invoice::factory()->create(['status' => 'unpaid']);
        Invoice::factory()->create(['status' => 'paid']);

        $response = $this->actingAs($this->admin)->get(route('admin.invoices.index', ['status' => 'unpaid']));
        $response->assertStatus(200);
    }

    // ── Payment Tests ──

    public function test_payments_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.payments.index'));
        $response->assertStatus(200);
    }

    public function test_payment_can_be_recorded(): void
    {
        $invoice = Invoice::factory()->create(['total_amount' => 350000, 'status' => 'unpaid']);
        $method = PaymentMethod::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_method_id' => $method->id,
            'amount' => 350000,
            'payment_date' => Carbon::now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('admin.payments.index'));
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 350000,
        ]);
    }

    public function test_full_payment_marks_invoice_as_paid(): void
    {
        $invoice = Invoice::factory()->create(['total_amount' => 350000, 'status' => 'unpaid']);
        $method = PaymentMethod::factory()->create();

        $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_method_id' => $method->id,
            'amount' => 350000,
            'payment_date' => Carbon::now()->format('Y-m-d'),
        ]);

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
    }

    public function test_partial_payment_marks_invoice_as_partially_paid(): void
    {
        $invoice = Invoice::factory()->create(['total_amount' => 350000, 'status' => 'unpaid']);
        $method = PaymentMethod::factory()->create();

        $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_method_id' => $method->id,
            'amount' => 150000,
            'payment_date' => Carbon::now()->format('Y-m-d'),
        ]);

        $invoice->refresh();
        $this->assertEquals('partially_paid', $invoice->status);
    }

    public function test_payment_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payments.store'), []);
        $response->assertSessionHasErrors(['invoice_id', 'payment_method_id', 'amount']);
    }
}
