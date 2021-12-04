<?php

namespace SimpleEtl;

interface TransformerInterface
{
    public function transform(mixed $row): mixed;
}
