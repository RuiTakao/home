<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\ExSessionComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\ExSessionComponent Test Case
 */
class ExSessionComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\ExSessionComponent
     */
    protected $ExSession;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->ExSession = new ExSessionComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ExSession);

        parent::tearDown();
    }
}
