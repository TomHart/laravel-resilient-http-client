<?php
namespace TomHart\HttpClient\Tests;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TomHart\HttpClient\ResilientServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ResilientServiceProvider::class
        ];
    }

    public function getStub(string $file): string
    {
        return file_get_contents(__DIR__.'/stubs/'.$file);
    }
}
