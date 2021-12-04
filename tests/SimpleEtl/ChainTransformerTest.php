<?php

namespace Tests\SimpleEtl;

use PHPUnit\Framework\TestCase;
use SimpleEtl\ChainTransformer;
use SimpleEtl\TransformerInterface;

class ChainTransformerTest extends TestCase
{
    public function test_it_should_transform_data_with_all_transformers(): void
    {
        $transformers = [
            $this->makeTransformer(fn($d) => "start $d"),
            $this->makeTransformer(fn($d) => "$d end"),
            $this->makeTransformer('strtoupper'),
        ];

        $transformer = new ChainTransformer(...$transformers);

        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = uniqid();
        }

        foreach ($data as $d) {
            $this->assertSame(strtoupper("start $d end"), $transformer->transform($d));
        }
    }

    private function makeTransformer(callable $transformer)
    {
        return new class($transformer) implements TransformerInterface {
            public function __construct(private $transformer)
            {
            }

            public function transform(mixed $row): mixed
            {
                return ($this->transformer)($row);
            }
        };
    }
}
