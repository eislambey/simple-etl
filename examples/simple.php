<?php

require __DIR__ . "/../vendor/autoload.php";

$runner = new \SimpleEtl\Runner();

$extractor = new class implements \SimpleEtl\ExtractorInterface {
    public function extract(): Generator
    {
        for ($i = 0; $i < 100; ++$i) {
            yield ["i" => $i];
        }
    }
};

$transformer = new class implements \SimpleEtl\TransformerInterface {
    public function transform(mixed $row): array
    {
        return [
            "number" => $row["i"],
            "hash" => md5($row["i"]),
        ];
    }
};

$loader = new \SimpleEtl\VarDumpLoader();

$runner->run($extractor, $transformer, $loader);
