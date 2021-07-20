<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200)
        ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show',['category' => $category->id]));
        $response->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testStore()
    {
        $response = $this->json('POST',route('categories.store',[
            'name' => 'teste'
        ]));

        $id = $response->json('id');
        $category = Category::find($id);

        $response->assertStatus(201)
            ->assertJson($category->toarray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'is_active' => false
        ]);

        $response = $this->json('PUT',route('categories.update',
            [ 'category' => $category->id]),
            [
                'name'=> 'teste 2',
                'description'=> 'teste',
                'is_active' => true
            ]);

        $id = $response->json('id');
        $category = Category::find($id);

        $response->assertStatus(200)
            ->assertJson($category->toarray())
            ->assertJsonFragment([
                'description'=> 'teste',
                'is_active' => true
            ]);
    }

    public function testDelete(){
        $category = factory(Category::class)->create();

        $this->json('DELETE',route('categories.destroy',
            [ 'category' => $category->id]));

        $category = Category::find($category->id);

        $this->assertNull($category);

    }
}
