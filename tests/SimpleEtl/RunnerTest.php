<?php

namespace Tests\SimpleEtl;

use Generator;
use PHPUnit\Framework\TestCase;
use SimpleEtl\ExtractorInterface;
use SimpleEtl\LoaderInterface;
use SimpleEtl\Runner;
use SimpleEtl\TransformerInterface;

class RunnerTest extends TestCase
{
    private Runner $runner;

    protected function setUp(): void
    {
        $this->runner = new Runner();
    }

    public function test_it_runs()
    {
        $extractor = new class implements ExtractorInterface {
            public int $rowCount = 100;

            public function extract(): Generator
            {
                for ($i = 0; $i < $this->rowCount; $i++) {
                    yield ["number" => $i];
                }
            }
        };

        $transformer = new class implements TransformerInterface {
            public int $called = 0;
            public function transform(mixed $row): mixed
            {
                $this->called++;
                $row["number"] *= 2;

                return $row;
            }
        };

        $loader = new class implements LoaderInterface {
            public array $rows = [];

            public function load(mixed $row): void
            {
                $this->rows[] = $row;
            }
        };

        $this->runner->run($extractor, $transformer, $loader);

        $this->assertSame($extractor->rowCount, $transformer->called);
        $this->assertSame($extractor->rowCount, count($loader->rows));

        foreach ($loader->rows as $i => $row) {
            $this->assertSame(["number" => $i * 2], $row);
        }

    }

}
