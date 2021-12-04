<?php

namespace SimpleEtl;

class NullTransformer implements TransformerInterface
{
    public function transform(mixed $row): mixed
    {
        return $row;
    }
}
