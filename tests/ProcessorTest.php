<?php

namespace Tests;

use Akibatech\Wysiwyg\Modifier\BbCode;
use Akibatech\Wysiwyg\Modifier\NlToBr;
use PHPUnit\Framework\TestCase;
use Akibatech\Wysiwyg\Processor;

class ProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function testConstructionAndInputOutput()
    {
        $expected  = 'Hello world!';
        $processor = new Processor();

        $processor->process($expected);

        $this->assertEquals($expected, $processor->getOutput());
    }

    /**
     * @test
     * @depends testConstructionAndInputOutput
     */
    public function testGetModifiersWhenEmpty()
    {
        $processor = new Processor();

        $this->assertEquals(0, count($processor->getModifiers()));
    }

    /**
     * @test
     * @depends testGetModifiersWhenEmpty
     */
    public function testAddModifier()
    {
        $processor = new Processor();
        $processor->addModifier(new NlToBr());

        $this->assertEquals(1, count($processor->getModifiers()));
    }

    /**
     * @test
     * @depends testAddModifier
     */
    public function testAddManyModifiers()
    {
        $processor = new Processor();

        $processor->addModifiers([
            new NlToBr(),
            new BbCode()
        ]);

        $this->assertEquals(2, count($processor->getModifiers()));
    }
}