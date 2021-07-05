<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Genre::class)->create();
        $genre = Genre::all();
        $this->assertCount(1,$genre);

        $genreKeys = array_keys($genre->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            ['id', 'name', 'is_active', 'deleted_at', 'created_at','updated_at'],
            $genreKeys
        );
    }

    public function testCreate(){
        $genre = Genre::create([
            'name' => 'teste'
        ]);
        $genre->refresh();

        $this->assertEquals(36, strlen($genre->id));
        $this->assertEquals('teste', $genre->name);
        $this->assertTrue($genre->is_active);
    }

    public function testUpdate(){
        $genre = factory(Genre::class)->create([
            'name' => 'test_name',
            'is_active' => false
        ]);

        $data = [
            'name' => 'test_name_updated',
            'is_active' => false
        ];
        $genre->update($data);

        foreach($data as $key => $value){
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete(){
        $genre = factory(Genre::class)->create();
        $genre->delete();
        $this->assertNull(Category::find($genre->id));
    }

}
