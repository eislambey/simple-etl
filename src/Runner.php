<?php

namespace SimpleEtl;

class Runner
{
    public function run(
        ExtractorInterface $extractor,
        TransformerInterface $transformer,
        LoaderInterface $loader,
    ): void
    {
        foreach ($extractor->extract() as $row) {
            $loader->load($transformer->transform($row));
        }
    }
}
