<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\LoanStatuses;
use Illuminate\Http\Response;

class LoanRepaymentTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateRepayment()
    {
        // Create a user
        $user = User::factory()->create();
        $loanStatus = LoanStatuses::factory()->create(['id' => LoanStatuses::APPROVED]);
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'status_id' => LoanStatuses::APPROVED,
            'amount'   => 2000,
            'term_duration' => 2
        ]);
        
        // Create the request payload
        $payload = [
            'amount' => 1000,
        ];

        $expectedRepayment = LoanRepayment::factory()->create([
            "loan_id" => $loan->id,
            "amount"   => $payload['amount']
        ]);


        // Mock the LoanService's createLoan method
        $this->mockLoanRepaymentService()
            ->shouldReceive('createRepayment')
            ->with($loan->id, $payload['amount'], $user->id)
            ->once()
            ->andReturn($expectedRepayment);

        // Send a POST request to the create endpoint
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/loan/'.$loan->id.'/repayment', $payload);

        // Assertions
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'id',
            'loan_id',
            'amount',
            'created_at',
            'updated_at',
        ]);
    }


    protected function mockLoanRepaymentService()
    {
        return $this->mock(\App\Services\LoanRepaymentService::class, function ($mock) {
            // Mock any necessary methods or dependencies of the LoanService
        });
    }
}
