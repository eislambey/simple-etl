<?php

namespace SimpleEtl;

class ChainLoader implements LoaderInterface
{
    /** @var \SimpleEtl\LoaderInterface[] */
    private array $loaders;

    public function __construct(LoaderInterface $loader, LoaderInterface ...$loaders)
    {
        $this->loaders = func_get_args();
    }

    public function load(mixed $row): void
    {
        foreach ($this->loaders as $loader) {
            $loader->load($row);
        }
    }
}
