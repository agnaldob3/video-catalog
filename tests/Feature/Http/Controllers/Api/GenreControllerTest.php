<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.index'));
        $response->assertStatus(200)
            ->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.show',['genre' => $genre->id]));
        $response->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testStore()
    {
        $response = $this->json('POST',route('genres.store',[
            'name' => 'teste genre'
        ]));

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(201)
            ->assertJson($genre->toarray());

    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'is_active' => false
        ]);

        $response = $this->json('PUT',route('genres.update',
            [ 'genre' => $genre->id]),
            [
                'name'=> 'teste update',
                'is_active' => true
            ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(200)
            ->assertJson($genre->toarray())
            ->assertJsonFragment([
                'name'=> 'teste update',
                'is_active' => true
            ]);
    }

    public function testDelete(){
        $genre = factory(Genre::class)->create();

        $this->json('DELETE',route('genres.destroy',
            [ 'genre' => $genre->id]));

        $genre = Category::find($genre->id);

        $this->assertNull($genre);

    }
}
