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
class FullPageBench extends PageBenchCase
{
    public function setUp(): void
    {
        $this->setUpData(warm: false);
    }

    public function benchFullForumIndexPage(): void
    {
        $app = $this->bootApp();

        try {
            $this->request($app, $this->homeUrl);
        } finally {
            $this->closeApp($app);
        }
    }

    public function benchFullPostPage(): void
    {
        $app = $this->bootApp();

        try {
            $this->request($app, $this->postUrl);
        } finally {
            $this->closeApp($app);
        }
    }
}
