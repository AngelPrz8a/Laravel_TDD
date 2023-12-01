<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Create a new repository
     * go to the direction
     * make sure exist the view (state 200 OK)
     * see if can identify the repository
     */
    public function test_home(): void
    {
        $repository = Repository::factory()->create();
        $this
            ->get("/")
            ->assertStatus(200)
            ->assertSee($repository->url);
    }
}
