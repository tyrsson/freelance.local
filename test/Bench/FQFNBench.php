<?php

declare(strict_types=1);

namespace Bench;

use App\ConfigProvider;

class FQFCBench
{
    private array $provider;

    public function __construct()
    {
        $this->provider = (new ConfigProvider())();
    }

    /**
     * @Revs(1500000)
     * @Iterations(10)
     */
    public function benchFullyQualified()
    {
        $result = \in_array('dependencies', $this->provider);
    }

    /**
     * @Revs(1500000)
     * @Iterations(10)
     */
    public function benchFallback()
    {
        $result = in_array('dependencies', $this->provider);
    }
}
