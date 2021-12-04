<?php

namespace SimpleEtl;

class VarDumpLoader implements LoaderInterface
{
    public function load(mixed $row): void
    {
        var_dump($row);
    }
}
