<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\LoanService;
use App\Models\Role;
use App\Models\User;
use App\Models\Loan;
use App\Models\LoanStatuses;
use Illuminate\Http\Response;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateLoanWithValidData()
    {
        // Create a user
        $user = User::factory()->create();
        $loanStatus = LoanStatuses::factory()->create();

        // Mock the LoanService's createLoan method
        $this->mockLoanService()
            ->shouldReceive('createLoan')
            ->with($user->id, 1000, 30)
            ->once()
            ->andReturn(Loan::factory()->create());

        // Create the request payload
        $payload = [
            'amount' => 1000,
            'term_duration' => 30,
        ];

        // Send a POST request to the create endpoint
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/loan', $payload);

        // Assertions
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'amount',
            'term_duration',
            'status_id',
            'created_at',
            'updated_at',
        ]);
    }

    public function testCreateLoanWithInvalidData()
    {
        // Create a user
        $user = User::factory()->create();

        // Create the request payload with missing required fields
        $payload = [];

        // Send a POST request to the create endpoint
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/loan', $payload);

        // Assertions
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['amount', 'term_duration']);
    }

    public function testAllGetLoansForUser()
    {
        // Create a user
        $user = User::factory()->create();
        // Mock the LoanService's createLoan method
        $loanStatus = LoanStatuses::factory()->create();
        $this->mockLoanService()
            ->shouldReceive('getLoansForUser')
            ->with($user->id)
            ->once()
            ->andReturn(Loan::factory()->create());
            
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/loans');
        
        // Assertions
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'amount',
            'term_duration',
            'status_id',
            'created_at',
            'updated_at',
        ]);
    }
    
    public function testGetLoanByID()
    {
        // Create a user
        $user = User::factory()->create();
        // Mock the LoanService's createLoan method
        $loanStatus = LoanStatuses::factory()->create();
        $loan = Loan::factory()->create();
        $this->mockLoanService()
            ->shouldReceive('getLoanById')
            ->with($loan->id, $user->id)
            ->once()
            ->andReturn($loan);
            
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/loan/'.$loan->id);
        
        // Assertions
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'amount',
            'term_duration',
            'status_id',
            'created_at',
            'updated_at',
        ]);
    }

    public function testLoanApprove()
    {
        // Create a user
        $user = User::factory()->create(["role_id" => Role::ADMIN]);
        // Mock the LoanService's createLoan method
        $loanStatus = LoanStatuses::factory()->create();
        $loan = Loan::factory()->create();
        $this->mockLoanService()
            ->shouldReceive('approveLoan')
            ->with($loan->id)
            ->once()
            ->andReturn($loan);
            
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/loan/approve/'.$loan->id);
        
        // Assertions
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'amount',
            'term_duration',
            'status_id',
            'created_at',
            'updated_at',
        ]);
    }

    public function testGetAllLoansForAdmin()
    {
        // Create a user
        $user = User::factory()->create(["role_id" => Role::ADMIN]);
        // Mock the LoanService's createLoan method
        $loanStatus = LoanStatuses::factory()->create();
        $loan = Loan::factory()->count(5)->create();
        $this->mockLoanService()
            ->shouldReceive('getAllLoans')
            ->andReturn($loan);
            
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/loans/all');
        
        // Assertions
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            "*" => [
                'id',
                'user_id',
                'amount',
                'term_duration',
                'status_id',
                'created_at',
                'updated_at',
            ]        
        ]);
    }

    protected function mockLoanService()
    {
        return $this->mock(\App\Services\LoanService::class, function ($mock) {
            // Mock any necessary methods or dependencies of the LoanService
        });
    }
}
