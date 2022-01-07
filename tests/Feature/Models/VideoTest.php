<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class videoTest extends TestCase
{
    use DatabaseMigrations;

    const RATING_LIST = ['L', '10', '12', '14', '16', '18'];

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
        'video_file',
        'thumb_file',
        'banner_file',
        'trailer_file'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'string',
        'opened' => 'boolean',
        'year_launched' => 'integer',
        'duration' => 'integer'
    ];

    public $incrementing = false;

    public function testRollbackCreate()
    {
        $hasError = false;
        try {
            Video::create([
                    'title' => 'title',
                    'description' => 'description',
                    'year_launched' => 2010,
                    'rating' => Video::RATING_LIST[0],
                    'duration' => 90,
                    'categories_id' => [0, 1, 2]
                ]);

        } catch (QueryException $exception) {
            $this->assertCount(0, Video::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testRollbackUpdate(){
        $video = factory(Video::class)->create();
        $oldTitle = $video->title;
        try {
            $video->update([
                'title' => 'title',
                'description' => 'description',
                'year_launched' => 2010,
                'rating' => Video::RATING_LIST[0],
                'duration' => 90,
                'categories_id' => [0, 1, 2]
            ]);

        } catch (QueryException $exception) {
            $this->assertDatabaseHas('video',[
                'title' => $oldTitle
            ]);
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testCreateWithBasicFields()
    {

        $video = Video::create(['title' => 'title',
                'description' => 'description',
                'year_launched' => 2010,
                'rating' => Video::RATING_LIST[0],
                'duration' => 90,] + $this->fileFieldsData);
        $video->refresh();

        $this->assertEquals(36, strlen($video->id));
        $this->assertFalse($video->opened);
        $this->assertDatabaseHas(
            'videos',
            ['title' => 'title',
                'description' => 'description',
                'year_launched' => 2010,
                'rating' => Video::RATING_LIST[0],
                'duration' => 90,] + $this->fileFieldsData + ['opened' => false]
        );

        $video = Video::create(['title' => 'title',
                'description' => 'description',
                'year_launched' => 2010,
                'rating' => Video::RATING_LIST[0],
                'duration' => 90,] + ['opened' => true]);
        $this->assertTrue($video->opened);
        $this->assertDatabaseHas('videos', ['opened' => true]);
    }
}
