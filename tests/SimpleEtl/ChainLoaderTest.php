<?php

namespace Tests\SimpleEtl;

use PHPUnit\Framework\TestCase;
use SimpleEtl\ChainLoader;
use SimpleEtl\LoaderInterface;
use SimpleEtl\VarDumpLoader;

class ChainLoaderTest extends TestCase
{
    public function test_it_should_load_using_all_loaders(): void
    {
        $arrayLoader = new class implements LoaderInterface {
            public array $store = [];

            public function load(mixed $row): void
            {
                $this->store[] = $row;
            }
        };
        $loaders = [$arrayLoader, new VarDumpLoader()];

        $rows = [];
        for ($i = 0; $i < 10; $i++) {
            $rows[] = ["i" => $i];
        }

        $loader = new ChainLoader(...$loaders);

        foreach ($rows as $i => $row) {
            $loader->load($row);
            $this->assertSame($row, $arrayLoader->store[$i]);
        }

        $expectedOutputString = "";
        foreach ($rows as $i => $_) {
            $expectedOutputString .= <<<EOF
array(1) {
  ["i"]=>
  int($i)
}

EOF;
        }
        $this->expectOutputString($expectedOutputString);
    }
}
