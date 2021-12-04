<?php

namespace SimpleEtl;

class ChainExtractor implements ExtractorInterface
{
    /** @var \SimpleEtl\ExtractorInterface[] */
    private array $extractors;

    public function __construct(ExtractorInterface $extractor, ExtractorInterface ...$extractors)
    {
        $this->extractors = func_get_args();
    }

    public function extract(): \Generator
    {
        foreach ($this->extractors as $extractor) {
            yield from $extractor->extract();
        }
    }
}
