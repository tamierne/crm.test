<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_create_user()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ]);

        $this->assertModelExists($user);
    }

    public function test_delete_user()
    {
        $user = User::inRandomOrder()->first();

        if($user) {
            $user->delete();
        }

        $this->assertSoftDeleted($user);
    }
}
