<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        // $this->user = User::factory(1)->make();
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
            'name' => 'Username',
            'email' => 'email@email.com',
            'password' => Hash::make('12345678'),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Username',
            'email' => 'email@email.com',
        ]);
    }

    public function test_delete_user()
    {
        $this->user = User::first();

        if($this->user) {
            $this->user->delete();
        }

        $this->assertTrue(true);
    }
}
