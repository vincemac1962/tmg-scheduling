<?php /** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpSwitchCanBeReplacedWithMatchExpressionInspection */

namespace Tests\Feature;

use App\Models\Upload;
use Tests\TestCase;
use Faker\Factory as Faker;

class UploadTest extends TestCase
{
    // returns a path based on the item type
    public static function getResourcePath($itemType)
    {
        switch ($itemType) {
            case 'ban':
                return 'storage/uploads/banner';
            case 'btn':
                return 'storage/uploads/button';
            case 'vid':
                return 'storage/uploads/mp4';
            // Add more cases as needed
            default:
                return 'storage/uploads';
        }
    }

    /**
     * @dataProvider uploadTypeProvider
     */
    public function test_an_upload_record_can_be_created($resource_type)
    {
        error_log("Resource type: $resource_type");
        $faker = Faker::create();
        $resource_filename = $faker->lexify('???????????????') . '.' . ($resource_type == 'vid' ? 'mp4' : 'png');
        $upload = Upload::create([
            'resource_type' => $resource_type,
            'resource_filename' => $resource_filename,
            'resource_path' => $this->getResourcePath($resource_type),
            'is_uploaded' => $faker->boolean,
            'uploaded_by' => $faker->randomElement([1, 2, 3]),
            'uploaded_at' => $faker->dateTimeThisYear,
        ]);

        $this->assertDatabaseHas('uploads', ['id' => $upload->id]);
    }

    public static function uploadTypeProvider()
    {
        $types = [
            ['btn'],
            ['ban'],
            ['vid'],
            // Add more types as needed
        ];
        error_log("Providing types: " . implode(', ', array_column($types, 0)));

        return $types;
    }


}
