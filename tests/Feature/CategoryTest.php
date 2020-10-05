<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;

    public function userGoesToCategoryIndex()
    {
        $this->visit('/');
        $this->assertViewIs('/');
    }

    public function testCategoryIndex()
    {
        $user = factory(User::class)->create();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->get(route('categories.index'))
            ->assertStatus(200);
    }

    public function testCreateNewCategory()
    {
        $user = factory(User::class)->create();

        $newCategories = factory(Category::class)->raw();

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->post(route('categories.store'), $newCategories)
            ->assertStatus(302);

        $response->assertRedirect('/categories');

        $this->assertDatabaseHas('categories', [
            'name' => $newCategories['name'],
            'description' => $newCategories['description'],
        ]);

        $this->assertViewIs('/categories');
    }

//    public function testGetEditFormCategory()
//    {
//        $user = factory(User::class)->create();
//
//        $newCategories = factory(Category::class)->create();
//
//        $response = $this->withExceptionHandling()
//            ->actingAs($user)
//            ->get(route('categories.edit'), $newCategories->id)
//            ->assertStatus(200);
//    }
}
