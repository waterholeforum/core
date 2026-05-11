<?php

namespace Benchmarks;

require_once __DIR__ . '/PageBenchCase.php';

use PhpBench\Attributes\AfterClassMethods;
use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeClassMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[BeforeClassMethods('setUpDatabase')]
#[AfterClassMethods('tearDownDatabase')]
#[AfterMethods('tearDownRequest')]
#[Revs(1)]
#[Iterations(10)]
#[Warmup(2)]
class WarmPageBench extends PageBenchCase
{
    #[BeforeMethods('setUpForumIndexPage')]
    public function benchForumIndexPage(): void
    {
        $this->request($this->homeUrl);
    }

    #[BeforeMethods('setUpPostPage')]
    public function benchPostPage(): void
    {
        $this->request($this->postUrl);
    }

    public function setUpForumIndexPage(): void
    {
        $this->setUpRequest();
        $this->request($this->homeUrl);
    }

    public function setUpPostPage(): void
    {
        $this->setUpRequest();
        $this->request($this->postUrl);
    }
}
