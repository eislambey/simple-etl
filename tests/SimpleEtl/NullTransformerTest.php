<?php

namespace Tests\SimpleEtl;

use PHPUnit\Framework\TestCase;
use SimpleEtl\NullTransformer;

class NullTransformerTest extends TestCase
{
    public function test_it_should_does_not_make_any_transformation()
    {
        $data = ["string" => uniqid(), "int" => mt_rand(), "object" => new \stdClass()];

        $tranformer = new NullTransformer();
        $transformedData = $tranformer->transform($data);

        $this->assertSame($data, $transformedData);
    }
}
