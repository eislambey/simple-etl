<?php

namespace Tests\SimpleEtl;

use Generator;
use PHPUnit\Framework\TestCase;
use SimpleEtl\ChainExtractor;
use SimpleEtl\ExtractorInterface;

class ChainExtractorTest extends TestCase
{
    public function test_it_should_extract_data_from_multiple_extractors()
    {
        $extractorCount = 5;
        $rows = [];
        $extractors = [];
        for ($i = 0; $i < $extractorCount; $i++) {
            $row = iterator_to_array($this->generateRows(1 + $extractorCount * $i));
            $rows = array_merge($rows, $row);
            $extractors[] = $this->makeExtractor($row);
        }

        $extractor = new ChainExtractor(...$extractors);

        $i = 0;
        foreach ($extractor->extract() as $row) {
            $this->assertSame($rows[$i], $row);
            $i++;
        }

        // Assert each extractor called
        foreach ($extractors as $_extractor) {
            $this->assertTrue($_extractor->called);
        }
    }

    private function makeExtractor(array $rows): ExtractorInterface
    {
        return new class($rows) implements ExtractorInterface {
            public bool $called = false;
            public function __construct(private array $rows)
            {
            }

            public function extract(): Generator
            {
                $this->called = true;
                foreach ($this->rows as $row) {
                    yield $row;
                }
            }
        };
    }

    private function generateRows(int $rowCount): Generator
    {
        for ($i = 0; $i < $rowCount; $i++) {
            yield ["i" => $i, "uniqid" => uniqid(), "microtime" => microtime(true)];
        }
    }
}
