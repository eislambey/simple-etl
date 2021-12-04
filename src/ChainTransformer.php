<?php

namespace SimpleEtl;

class ChainTransformer implements TransformerInterface
{
    /** @var \SimpleEtl\TransformerInterface[] */
    private array $transformers;

    public function __construct(TransformerInterface $transformer, TransformerInterface ...$transformers)
    {
        $this->transformers = func_get_args();
    }

    public function transform(mixed $row): mixed
    {
        foreach ($this->transformers as $transformer) {
            $row = $transformer->transform($row);
        }

        return $row;
    }
}
