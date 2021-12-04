<?php

namespace Tests\SimpleEtl;

use PHPUnit\Framework\TestCase;
use SimpleEtl\VarDumpLoader;

class VarDumpLoaderTest extends TestCase
{
    public function test_it_should_dump_given_data()
    {
        $data = ["string" => uniqid(), "int" => mt_rand(), "object" => new \stdClass()];

        $loader = new VarDumpLoader();
        $loader->load($data);

        $objectId = spl_object_id($data["object"]);
        $expectedOutput = <<<EOF
array(3) {
  ["string"]=>
  string(13) "{$data['string']}"
  ["int"]=>
  int({$data['int']})
  ["object"]=>
  object(stdClass)#$objectId (0) {
  }
}

EOF;

        $this->expectOutputString($expectedOutput);
    }
}
