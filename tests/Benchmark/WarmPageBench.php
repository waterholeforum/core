<?php

namespace Benchmarks;

require_once __DIR__ . '/PageBenchCase.php';

use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[BeforeMethods('setUp')]
#[AfterMethods('tearDown')]
#[Revs(1)]
#[Iterations(10)]
#[Warmup(2)]
class WarmPageBench extends PageBenchCase
{
    public function setUp(): void
    {
        $this->setUpData(warm: true);
    }

    public function benchForumIndexPage(): void
    {
        $this->request($this->app, $this->homeUrl);
    }

    public function benchPostPage(): void
    {
        $this->request($this->app, $this->postUrl);
    }
}
