<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testUserCreation()
    {

        $name = $this->faker->name();
        $email = $this->faker->email();

        $response = $this->postJson('/api/auth/signup', [
            'name' => $name,
            'email' => $email,
            'password' => $this->password,
            'password_confirmation' => $this->password
        ]);


        $response
            ->assertStatus(201)
            ->assertExactJson([
                'message' => "Successfully created user!",
            ]);
    }

    public function testUserLogin()
    {
        $name = $this->faker->name();
        $email = $this->faker->email();

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($this->password)
        ]);

        $user->save();

        $response = $this->postJson('/api/auth/login', [
            'email' => $email,
            'password' => $this->password
        ]);


        $response->assertStatus(200);
        $this->assertAuthenticated();
    }
}
