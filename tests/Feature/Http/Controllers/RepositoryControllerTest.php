<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Trying to access the route is redirected to the login
     */
    public function test_guest(): void
    {
        $this->get("repositories")->assertRedirect("login");    //index
        $this->get("repositories/1")->assertRedirect("login");    //show
        $this->get("repositories/1/edit")->assertRedirect("login");    //edit
        $this->put("repositories/1")->assertRedirect("login");    //update
        $this->delete("repositories/1")->assertRedirect("login");    //destroy
        $this->get("repositories/create")->assertRedirect("login");    //create
        $this->post("repositories",[])->assertRedirect("login");    //store
    }

    /**
     * Create a repository and user
     * acts as an user
     * go to the route to see all repositories
     * make sure exist the view and don't has any repository
     */
    public function test_index_empty():void
    {
        Repository::factory()->create();    // id = 1
        $user = User::factory()->create();  // id = 2

        $this
            ->actingAs($user)
            ->get("repositories")
            ->assertStatus(200)
            ->assertSee("No hay repositorios");
    }

    /**
     * Create an user and repository with a relation between them
     * act as the user
     * go to the route
     * see if are the id and url of the repository
     */
    public function test_index_with_data():void
    {
        $user = User::factory()->create();  // id = 1
        $repository = Repository::factory()->create(["user_id"=>$user->id]);    // user_id = 1
       
        $this
            ->actingAs($user)
            ->get("repositories")
            ->assertStatus(200)
            ->assertSee($repository->id)
            ->assertSee($repository->url);
    }

    /**
     * create an array widn fake data
     * create an user, act as it
     * go to the route and send the data
     * check if is redirected and if the data are in the DB
     */
    public function test_store():void
    {
        $data = [
            "url"=>$this->faker->url,
            "description"=>$this->faker->text,
        ];

        $user = User::factory()->create();

        $this
        ->actingAs($user)
        ->post("repositories",$data)
        ->assertRedirect("repositories");

        $this
        ->assertDatabaseHas("repositories",$data);
    }

    /**
     * Create an user, act as it
     * go to the route to create a new repository
     * check if exist the view
     */
    public function test_create():void
    {
        $user = User::factory()->create();

        $this
        ->actingAs($user)
        ->get("repositories/create")
        ->assertStatus(200);
    }

    /**
     * Create an user and repository with a relation
     * create an array with fake data
     * act as the user
     * go to the route to update the repository and send the data
     * check if is redirected and if exists the new data in the DB
     */
    public function test_update():void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(["user_id"=>$user->id]);

        $data = [
            "url"=>$this->faker->url,
            "description"=>$this->faker->text,
        ];

        $this
        ->actingAs($user)
        ->put("repositories/$repository->id",$data)
        ->assertRedirect("repositories/$repository->id/edit");

        $this
        ->assertDatabaseHas("repositories",$data);
    }

    /**
     * create an user and repository with a relation between them
     * create an array with fake data
     * act as the user
     * go to update the repository with the data
     * check if don't have access
     */
    public function test_update_policy():void
    {
        $user = User::factory()->create();              // id = 1
        $repository = Repository::factory()->create();  // user_id = 2

        $data = [
            "url"=>$this->faker->url,
            "description"=>$this->faker->text,
        ];

        $this
        ->actingAs($user)
        ->put("repositories/$repository->id",$data)
        ->assertStatus(403);
    }

    /**
     * create an user, act as it
     * go to the route with empty data
     * check the state is 302 and receive errors
     */
    public function test_validate_store():void
    {
        $user = User::factory()->create();

        $this
        ->actingAs($user)
        ->post("repositories",[])
        ->assertStatus(302)
        ->assertSessionHasErrors(["url","description"]);
    }

    /**
     * create a repository(create an user) and user
     * act as the user
     * go to update the repository
     * check if the state is 302 and has error for empty data
     */
    public function test_validate_update():void
    {
        $repository = Repository::factory()->create();

        $user = User::factory()->create();

        $this
        ->actingAs($user)
        ->put("repositories/$repository->id",[])
        ->assertStatus(302)
        ->assertSessionHasErrors("url","description"); 
    }

    /**
     * Create an user and repository with a relation
     * act as the user
     * go to delete the repository
     * check if is redirected and in the DB doesn't exist the repository
     */
    public function test_destroy():void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(["user_id"=>$user->id]);
        
        $this
        ->actingAs($user)
        ->delete("repositories/$repository->id")
        ->assertRedirect("repositories");

        $this
        ->assertDatabaseMissing("repositories",["id"=>$repository->id]);
    }

    /**
     * create an user and repository(created an user)
     * act as the user
     * go to delete the repository
     * check if has a state 403 (unauthorized)
     */
    public function test_destroy_policy():void
    {
        $user = User::factory()->create();              // id=1
        $repository = Repository::factory()->create();  // id=2
        $this
        ->actingAs($user)
        ->delete("repositories/$repository->id")
        ->assertStatus(403);
    }

    /**
     * Create an user and repository with a relation
     * act as the user
     * go to show the repository
     * check if can see it that mean state 200
     */
    public function test_show():void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(["user_id"=>$user->id]);

        $this
        ->actingAs($user)
        ->get("repositories/$repository->id")
        ->assertStatus(200);
    }

    /**
     * Create an user and repository(created an user)
     * act as the user
     * go to show the repository
     * check a state 403 (unauthorized)
     */
    public function test_show_policy():void
    {
        $user = User::factory()->create();              // id = 1
        $repository = Repository::factory()->create();  // user_id = 2

        $this
        ->actingAs($user)
        ->get("repositories/$repository->id")
        ->assertStatus(403);
    }

    /**
     * Create an user and repository with a relation
     * act as the user
     * go to the form to edit
     * check be authorized and see the url and description
     */
    public function test_edit():void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(["user_id"=>$user->id]);

        $this
        ->actingAs($user)
        ->get("repositories/$repository->id/edit")
        ->assertStatus(200)
        ->assertSee($repository->url)
        ->assertSee($repository->description);
    }

    /**
     * Create an user and repository(created an user)
     * act as the user
     * go to the form to edit
     * check if is a state 403(unauthorized)
    */
    public function test_edit_policy():void
    {
        $user = User::factory()->create();              // id = 1
        $repository = Repository::factory()->create();  // user_id = 2

        $this
        ->actingAs($user)
        ->get("repositories/$repository->id/edit")
        ->assertStatus(403);
    }
}
