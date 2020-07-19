<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test if users can successfully sign up to the app
     *
     * @return void
     */
    public function testUsersCanSignUp()
    {
        $data = $this->data();

        $this->post(route('auth.register'), $data)->assertJsonStructure([
            'status',
            'user',
            'token'
        ])->assertStatus(200);

        $this->assertDatabaseHas('users', ['name' => $data['name'], 'email' => $data['email']]);
    }

    /**
     * Test if sign up validations are accurate
     *
     * @return void
     */
    public function testSignUpValidations()
    {
        $data = $this->data();

        $this->post(route('auth.register'), array_merge($data, ['email' => '']))->assertJson([
            'status'  => 'error',
            'message' => 'The email field is required.',
        ])->assertStatus(422);

        $this->post(route('auth.register'), array_merge($data, ['name' => '']))->assertJson([
            'status'  => 'error',
            'message' => 'The name field is required.',
        ])->assertStatus(422);

        $this->post(route('auth.register'), array_merge($data, ['password' => '']))->assertJson([
            'status'  => 'error',
            'message' => 'The password field is required.',
        ])->assertStatus(422);

        $this->post(route('auth.register'), array_merge($data, ['password' => Str::random(2)]))->assertJson([
            'status'  => 'error',
            'message' => 'The password must be at least 6 characters.',
        ])->assertStatus(422);
        
        $this->post(route('auth.register'), array_merge($data, ['password_confirmation' => '']))->assertJson([
            'status'  => 'error',
            'message' => 'The password confirmation does not match.',
        ])->assertStatus(422);
    }

    /**
     * Test if users can successfully login to the app
     *
     * @return void
     */
    public function testUsersCanLogin()
    {
        $user = factory(User::class)->create();

        $this->post(route('auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertJson([
            'status' => 'success',
            'user'   => $user->toArray(),
        ])->assertJsonStructure([
            'status',
            'user',
            'token',
        ])->assertStatus(200);
    }

    /**
     * Invalid users can not login to the app
     *
     * @return void
     */
    public function testInvalidUsersCanNotLogin()
    {
        $this->post(route('auth.login'), [
            'email'    => $this->faker->safeEmail,
            'password' => 'password',
        ])->assertJson([
            'status'  => 'error',
            'message' => 'Invalid email or password provided.',
        ])->assertStatus(401);
    }

    /**
     * Test if login validations are accurate
     *
     * @return void
     */
    public function testLoginValidations()
    {
        $this->post(route('auth.login'), ['email' => ''])->assertJson([
            'status'  => 'error',
            'message' => 'The email field is required.',
        ])->assertStatus(422);

        $this->post(route('auth.login'), [
            'email'    => $this->faker->safeEmail,
            'password' => '',
        ])->assertJson([
            'status'  => 'error',
            'message' => 'The password field is required.',
        ])->assertStatus(422);
    }

    /**
     * Test if users can logout
     *
     * @return void
     */
    public function testUsersCanLogOut()
    {
        $user = factory(User::class)->create();

        $this->withoutExceptionHandling();
        
        auth()->login($user);
        
        $this->actingAs($user)->post(route('auth.logout'), [])->assertJson([
            'status'  => 'success',
            'message' => 'You just logged out',
        ])->assertStatus(200);
    }

    /**
     * Valid data for testing
     *
     * @return array
     */
    public function data()
    {
        return [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->safeEmail,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
    }
}
