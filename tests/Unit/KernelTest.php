<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Kernel;
use PHPUnit\Framework\TestCase;

final class KernelTest extends TestCase
{
    public function testBoot(): void
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        self::assertSame('test', $kernel->getEnvironment());
    }
}
