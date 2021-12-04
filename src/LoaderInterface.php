<?php

namespace SimpleEtl;

interface LoaderInterface
{
    public function load(mixed $row): void;
}
