<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Http\Resources\CategoryResource;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;
use Tests\Traits\TestResources;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves, TestResources;

    private $serializedFields = [
        'id',
        'name',
        'description',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['per_page' => 15]
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->serializedFields
                ],
                'links' => [],
                'meta' => [],
            ]);

        $resource = CategoryResource::collection(collect([$this->category]));
        $this->assertResource($response, $resource);

    }

    public function testShow()
    {
        $response = $this->get(route('categories.show', ['category' => $this->category->id]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->serializedFields
            ]);

        $id = $response->json('data.id');
        $resource = new CategoryResource(Category::find($id));
        $this->assertResource($response, $resource);
    }


//    public function testShow()
//    {
//        $category = factory(Category::class)->create();
//        $response = $this->get(route('categories.show',['category' => $category->id]));
//        $response->assertStatus(200)
//            ->assertJson($category->toArray());
//    }
//
//    public function testStore()
//    {
//        $response = $this->json('POST',route('categories.store',[
//            'name' => 'teste'
//        ]));
//
//        $id = $response->json('id');
//        $category = Category::find($id);
//
//        $response->assertStatus(201)
//            ->assertJson($category->toarray());
//        $this->assertTrue($response->json('is_active'));
//        $this->assertNull($response->json('description'));
//    }
//
//    public function testUpdate()
//    {
//        $category = factory(Category::class)->create([
//            'is_active' => false
//        ]);
//
//        $response = $this->json('PUT',route('categories.update',
//            [ 'category' => $category->id]),
//            [
//                'name'=> 'teste 2',
//                'description'=> 'teste',
//                'is_active' => true
//            ]);
//
//        $id = $response->json('id');
//        $category = Category::find($id);
//
//        $response->assertStatus(200)
//            ->assertJson($category->toarray())
//            ->assertJsonFragment([
//                'description'=> 'teste',
//                'is_active' => true
//            ]);
//    }
//
//    public function testDelete(){
//        $category = factory(Category::class)->create();
//
//        $this->json('DELETE',route('categories.destroy',
//            [ 'category' => $category->id]));
//
//        $category = Category::find($category->id);
//
//        $this->assertNull($category);
//
//    }
    protected function model()
    {
        return Category::class;
    }

    protected function routeStore()
    {
        return route('categories.store');
    }

    protected function routeUpdate()
    {
        return route('categories.update', ['category' => $this->category->id]);
    }
}
