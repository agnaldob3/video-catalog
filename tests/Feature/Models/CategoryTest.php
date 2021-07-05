<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Category::class)->create();
        $category = Category::all();
        $this->assertCount(1,$category);
        $categoryKeys = array_keys($category->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $categoryKeys
        );
    }

    public function testCreate(){
        $category = Category::create([
            'name' => 'agnaldo'
        ]);
        $category->refresh();

        $this->assertEquals(36,strlen($category->id));
        $this->assertEquals('agnaldo',$category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

    }

    public function testUpdate(){
        $category = factory(Category::class)->create([
                'description' => 'test_description',
                'is_active' => false
            ]);
        $data = [
            'name' => 'test_name_updated',
            'description' => 'test_description',
            'is_active' => false
        ];
        $category->update($data);

        foreach($data as $key => $value){
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete(){
        $category = factory(Category::class)->create();
        $category->delete();
        $this->assertNull(Category::find($category->id));
    }


}
