<?php

namespace Benchmarks;

require_once __DIR__ . '/PageBenchCase.php';

use PhpBench\Attributes\AfterClassMethods;
use PhpBench\Attributes\BeforeClassMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[BeforeClassMethods('setUpDatabase')]
#[AfterClassMethods('tearDownDatabase')]
#[Revs(1)]
#[Iterations(10)]
#[Warmup(2)]
class FullPageBench extends PageBenchCase
{
    public function benchFullForumIndexPage(): void
    {
        $this->setUpRequest();

        try {
            $this->request($this->homeUrl);
        } finally {
            $this->tearDownRequest();
        }
    }

    public function benchFullPostPage(): void
    {
        $this->setUpRequest();

        try {
            $this->request($this->postUrl);
        } finally {
            $this->tearDownRequest();
        }
    }
}
