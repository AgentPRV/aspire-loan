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

class UserAPITest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegister()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create the request payload
        $payload = [
            'name' => 'Aspire Tester',
            'email' => 'tester@aspire.com',
            'password' => 'password123',
        ];

        // Mock the LoanService's createLoan method
        $this->mockUserService()
            ->shouldReceive('registerUser')
            ->with($payload)
            ->once()
            ->andReturn($user);

        // Send a POST request to the create endpoint
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/register', $payload);

        // Assertions
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function testUserLogin()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create the request payload
        $payload = [
            'email' => 'tester@aspire.com',
            'password' => 'password123',
            'device_name' => 'Test Device',
        ];

        // Mock the LoanService's createLoan method
        $this->mockUserService()
            ->shouldReceive('loginUser')
            ->once()
            ->with($payload)
            ->andReturn('token123');

        // Send a POST request to the create endpoint
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/login', $payload);

        // Assertions
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'token'
        ]);
    }


    protected function mockUserService()
    {
        return $this->mock(\App\Services\UserService::class, function ($mock) {
            // Mock any necessary methods or dependencies of the LoanService
        });
    }
}
