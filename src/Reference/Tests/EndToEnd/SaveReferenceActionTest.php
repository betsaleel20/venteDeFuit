<?php

namespace Shop\Reference\Tests\EndToEnd;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class SaveReferenceActionTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_reference()
    {
        $data = [
          'label' => 'The reference label',
          'price' => 2000
        ];
        $response = $this->postJson('app/reference/save', $data);
        $response->assertStatus(200);
        $this->assertTrue($response->json('status'));
        $this->assertTrue($response->json('isSaved'));
        $this->assertNotNull($response->json('referenceId'));
    }

}
