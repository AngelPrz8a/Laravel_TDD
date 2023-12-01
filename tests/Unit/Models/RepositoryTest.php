<?php

namespace Tests\Unit\Models;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a repository(Created an user)
     * check if has a relation with a user
     */
    public function test_belongs_to_user(): void
    {
        $repository = Repository::factory()->create();
        //dd($repository->user)
        $this->assertInstanceOf(User::class, $repository->user);
    }
}
