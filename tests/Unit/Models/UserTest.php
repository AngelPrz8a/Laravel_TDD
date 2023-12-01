<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Make sure the relation function (user-repository) work and return a collection
     */
    public function test_has_many_repositories(): void
    {
        $user = new User();
        $this->assertInstanceOf(Collection::class, $user->repositories);
    }
}
