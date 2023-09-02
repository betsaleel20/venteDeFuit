<?php

namespace Shop\Reference\Tests\EndToEnd;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Shop\Reference\Infrastructure\Model\TheReference;
use Tests\TestCase;

class GetAllReferenceActionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_get_all_references()
    {
        $this->buildSUT();

        $response = $this->getJson('app/references/all');

        $response->assertStatus(200);
        $this->assertCount(5,$response->json('references'));
    }

    private function buildSUT():void
    {
        TheReference::factory(5)->create();
    }

}
