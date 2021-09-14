<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;

 class BasicCrudControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::createTable();
        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    public function testIndex(){
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
        $resource = $this->controller->index();
        $serialized = $resource->response()->getData(true);
        $this->assertEquals(
            [$category->toArray()],
            $serialized['data']
        );
        $this->assertArrayHasKey('meta', $serialized);
        $this->assertArrayHasKey('links', $serialized);
    }

     public function testStore()
     {
         $request = \Mockery::mock(Request::class);
         $request
             ->shouldReceive('all')
             ->once()
             ->andReturn(['name' => 'test_name', 'description' => 'test_description']);

         $resource = $this->controller->store($request);
         $serialized = $resource->response()->getData(true);
         $this->assertEquals( CategoryStub::first()->toArray(), $serialized['data']);
     }

     public function testShow()
     {
         $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
         $resource = $this->controller->show($category->id);
         $serialized = $resource->response()->getData(true);
         $this->assertEquals($category->toArray(), $serialized['data']);
     }

     public function testUpdate()
     {
         $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
         $request = \Mockery::mock(Request::class);
         $request->shouldReceive('all')
             ->once()
             ->andReturn(['name' => 'test_changed', 'description' => 'test_description_changed']);
         $resource = $this->controller->update($request, $category->id);
         $serialized = $resource->response()->getData(true);
         $category->refresh();
         $this->assertEquals( $category->toArray(), $serialized['data']);
     }

     public function testDestroy()
     {
         $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
         $response = $this->controller->destroy($category->id);
         $this
             ->createTestResponse($response)
             ->assertStatus(204);
         $this->assertCount(0, CategoryStub::all());
     }

}
