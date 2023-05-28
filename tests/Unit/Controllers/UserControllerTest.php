<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery\MockInterface;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    private $userService;
    private $userController;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a mock instance of the UserService
        $this->userService = $this->mock(UserService::class);

        // Create an instance of the UserController with the mock UserService
        $this->userController = new UserController($this->userService);
    }

    public function testRegister()
    {
        // Create a request with valid user data
        $request = new Request([
            'name' => 'Aspire Tester',
            'email' => 'tester@aspire.com',
            'password' => 'password123',
        ]);

        // Expect the UserService's registerUser method to be called with the request data
        $this->userService->shouldReceive('registerUser')
            ->once()
            ->with($request->all());

        // Call the register method of the UserController
        $response = $this->userController->register($request);

        // Assert the response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'User registered successfully'], $response->getData(true));
    }

    public function testLogin()
    {
        // Create a request with valid login credentials
        $request = new Request([
            'email' => 'tester@aspire.com',
            'password' => 'password123',
            'device_name' => 'Test Device',
        ]);

        // Expect the UserService's loginUser method to be called with the request data
        $this->userService->shouldReceive('loginUser')
            ->once()
            ->with($request->all())
            ->andReturn('token123');

        // Call the login method of the UserController
        $response = $this->userController->login($request);

        // Assert the response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['token' => 'token123'], $response->getData(true));
    }
}
