<?php

namespace SaltLaravel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SaltLaravel\Support\ResourceSegment;

/**
 * The collection a resource controller serves, read from the request: never an action word such as `restore`.
 */
class ResourceSegmentTest extends TestCase
{
    /** @return array<string, array{0: string, 1: string}> */
    public static function paths(): array
    {
        return [
            'the collection' => ['api/v1/categories', 'categories'],
            'one record' => ['api/v1/categories/9b1d', 'categories'],
            'the trash' => ['api/v1/categories/trash', 'categories'],
            'import' => ['api/v1/categories/import', 'categories'],
            'a record in the trash' => ['api/v1/categories/9b1d/trashed', 'categories'],
            'restore one' => ['api/v1/categories/9b1d/restore', 'categories'],
            'restore the selected' => ['api/v1/categories/selected/restore', 'categories'],
            'restore all' => ['api/v1/categories/all/restore', 'categories'],
            'delete one for good' => ['api/v1/categories/9b1d/delete', 'categories'],
            'delete the selected for good' => ['api/v1/categories/selected/delete', 'categories'],
            'delete all for good' => ['api/v1/categories/all/delete', 'categories'],
            'move the selected to the trash' => ['api/v1/categories/selected', 'categories'],
            'a route with no prefix' => ['categories/9b1d/restore', 'categories'],
            'a collection with an underscore' => ['api/v1/product_variant_items/7/delete', 'product_variant_items'],
        ];
    }

    /** @dataProvider paths */
    public function test_reads_the_collection_from_the_path(string $path, string $expected): void
    {
        $this->assertSame($expected, ResourceSegment::collection(explode('/', $path)));
    }

    public function test_prefers_the_routes_collection_parameter(): void
    {
        $this->assertSame('brands', ResourceSegment::collection(['api', 'v1', 'brands', '4', 'restore'], 'brands'));
        $this->assertSame('brands', ResourceSegment::collection(['x', 'y', 'z'], 'brands'));
    }

    public function test_falls_back_to_the_path_when_the_parameter_is_empty(): void
    {
        $this->assertSame('categories', ResourceSegment::collection(['api', 'v1', 'categories', '4', 'restore'], ''));
        $this->assertSame('categories', ResourceSegment::collection(['api', 'v1', 'categories', '4', 'restore'], null));
    }

    public function test_has_no_collection_for_an_empty_path(): void
    {
        $this->assertNull(ResourceSegment::collection([]));
    }

    public function test_keeps_a_lone_word_that_happens_to_be_an_action(): void
    {
        // A path of one segment has nothing before it to be the collection.
        $this->assertSame('restore', ResourceSegment::collection(['restore']));
    }
}
