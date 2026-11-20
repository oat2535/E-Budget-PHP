<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class BudgetCreateTest extends TestCase
{
    protected function getTestUser(): User
    {
        $user = new User(['username' => 'test_officer']);
        $user->is_superuser = false;
        return $user;
    }

    public function test_budget_add_vet_renders(): void
    {
        $response = $this->actingAs($this->getTestUser())->get('/add');
        $response->assertStatus(200);
        $response->assertSee('VET Manpower');
    }

    public function test_budget_add_non_vet_renders(): void
    {
        $response = $this->actingAs($this->getTestUser())->get('/add_non_vet');
        $response->assertStatus(200);
        $response->assertSee('NON VET Manpower');
    }

    public function test_budget_add_adjustment_renders(): void
    {
        $response = $this->actingAs($this->getTestUser())->get('/add_adjustment');
        $response->assertStatus(200);
        $response->assertSee('Position Adjustment');
    }

    public function test_budget_add_medical_equipment_renders(): void
    {
        $response = $this->actingAs($this->getTestUser())->get('/add_medical_equipment');
        $response->assertStatus(200);
        $response->assertSee('Medical Equipment');
    }

    public function test_budget_add_gl_entry_renders(): void
    {
        $response = $this->actingAs($this->getTestUser())->get('/add_gl_entry');
        $response->assertStatus(200);
    }

    public function test_budget_add_revenue_renders(): void
    {
        $response = $this->actingAs($this->getTestUser())->get('/add_revenue');
        $response->assertStatus(200);
    }

    public function test_budget_add_post_missing_cost_center_returns_error(): void
    {
        $payload = [
            [
                'position_name' => 'Veterinarian',
                'salary' => 30000,
                'monthly_data' => [
                    'jan' => ['headcount' => 1, 'cost' => 30000],
                ],
            ],
        ];

        $response = $this->actingAs($this->getTestUser())->postJson('/add', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'error',
            'message' => 'กรุณาเลือก Cost Center',
        ]);
    }

    public function test_budget_add_post_invalid_headcount_returns_error(): void
    {
        $payload = [
            [
                'cost_center_name' => 'CC1',
                'position_name' => 'Veterinarian',
                'salary' => 30000,
                'monthly_data' => [
                    'jan' => ['headcount' => 1.5, 'cost' => 45000],
                ],
            ],
        ];

        $response = $this->actingAs($this->getTestUser())->postJson('/add', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'error',
        ]);
        $this->assertStringContainsString('จำนวนเต็มเท่านั้น', $response->json('message'));
    }

    public function test_fix_asset_purchase_price_under_5000_rejected(): void
    {
        $payload = [
            [
                'cost_center_name' => 'CC1',
                'item_name' => 'Thermometer',
                'purchase_price' => 2500,
                'monthly_data' => [
                    'jan' => ['headcount' => 1, 'cost' => 2500],
                ],
            ],
        ];

        $response = $this->actingAs($this->getTestUser())->postJson('/add_medical_equipment', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'error',
        ]);
        $this->assertStringContainsString('5,000 บาทขึ้นไป', $response->json('message'));
    }
}
