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

$transformer2 = new class implements \SimpleEtl\TransformerInterface {
    public function transform(mixed $row): array
    {
        return ["row" => $row];
    }
};

$loader = new \SimpleEtl\VarDumpLoader();
$echoLoader = new class implements \SimpleEtl\LoaderInterface {
    public function load(mixed $row): void
    {
        echo $row["row"]["number"], " loaded\n";
    }
};

$runner->run(
    $extractor,
    new \SimpleEtl\ChainTransformer($transformer, $transformer2),
    new \SimpleEtl\ChainLoader($loader, $echoLoader),
);
