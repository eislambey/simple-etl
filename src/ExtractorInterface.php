<?php

namespace SimpleEtl;

use Generator;

interface ExtractorInterface
{
    public function extract(): Generator;
}
