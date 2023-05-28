<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private $userService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an instance of the UserService
        $this->userService = new UserService();
    }

    public function testRegisterUser()
    {
        // Create a test user data
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];
        $role = Role::factory()->create([
            'id' => Role::USER
        ]);
        // Call the registerUser method of the UserService
        $this->userService->registerUser($userData);

        // Perform assertions on the created user
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role_id' => Role::USER,
        ]);
    }

    public function testLoginUserWithValidCredentials()
    {
        // Create a test user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Create a test login request
        $loginRequest = [
            'email' => $user->email,
            'password' => 'password123',
            'device_name' => 'Test Device',
        ];

        // Call the loginUser method of the UserService
        $token = $this->userService->loginUser($loginRequest);

        // Perform assertions on the returned token
        $this->assertNotEmpty($token);
    }

    public function testLoginUserWithInvalidCredentials()
    {
        // Create a test user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Create a test login request with invalid password
        $invalidLoginRequest = [
            'email' => $user->email,
            'password' => 'incorrectpassword',
            'device_name' => 'Test Device',
        ];

        // Expect a ValidationException to be thrown
        $this->expectException(ValidationException::class);

        // Call the loginUser method of the UserService
        $this->userService->loginUser($invalidLoginRequest);
    }
}
