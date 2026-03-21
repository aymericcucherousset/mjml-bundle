<?php

namespace NotFloran\MjmlBundle\Tests\Renderer;

use NotFloran\MjmlBundle\Renderer\BinaryRenderer;
use NotFloran\MjmlBundle\Tests\AbstractTestCase;
use Yoast\PHPUnitPolyfills\Polyfills\AssertStringContains;

class BinaryRendererTest extends AbstractTestCase
{
    use AssertStringContains;

    public function testBasicRender()
    {
        $renderer = new BinaryRenderer($this->getMjmlBinary(), false, 'strict');
        $html = $renderer->render(file_get_contents(__DIR__.'/../fixtures/basic.mjml'));

        self::assertStringContainsString('html', $html);
        self::assertStringContainsString('Hello Floran from MJML and Symfony', $html);
    }

    public function testInvalidRender()
    {
        $this->expectException(\RuntimeException::class);

        $renderer = new BinaryRenderer($this->getMjmlBinary(), false, 'strict');
        $renderer->render(file_get_contents(__DIR__.'/../fixtures/invalid.mjml'));
    }

    public function testInvalidRenderWithSkipValidationLevel()
    {
        $renderer = new BinaryRenderer($this->getMjmlBinary(), false, 'skip');
        $html = $renderer->render(file_get_contents(__DIR__.'/../fixtures/invalid.mjml'));

        self::assertStringContainsString('html', $html);
    }

    public function testInvalidRenderWithSoftValidationLevel()
    {
        $renderer = new BinaryRenderer($this->getMjmlBinary(), false, 'soft');
        $html = $renderer->render(file_get_contents(__DIR__.'/../fixtures/invalid.mjml'));

        self::assertStringContainsString('html', $html);
    }

    public function testBinaryNotFound()
    {
        $this->expectException(\RuntimeException::class);

        $renderer = new BinaryRenderer('mjml-not-found', false, 'strict');
        $renderer->render(file_get_contents(__DIR__.'/../fixtures/basic.mjml'));
    }

    public function testUseNode()
    {
        $renderer = new BinaryRenderer($this->getMjmlBinary(), false, 'strict', $this->getNode());
        $html = $renderer->render(file_get_contents(__DIR__.'/../fixtures/basic.mjml'));

        self::assertStringContainsString('html', $html);
    }

    /**
     * @dataProvider mjmlVersionDataProvider
     */
    public function testUseMjmlVersion(int $mjmlVersion)
    {
        $renderer = new BinaryRenderer($this->getMjmlBinary(), false, 'strict', null, $mjmlVersion);
        $html = $renderer->render(file_get_contents(__DIR__.'/../fixtures/basic.mjml'));

        self::assertStringContainsString('html', $html);
        self::assertStringContainsString('Hello Floran from MJML and Symfony', $html);
    }

    public function mjmlVersionDataProvider()
    {
        yield ['old version' => 3];
        yield ['actual version' => 4];
    }
}
