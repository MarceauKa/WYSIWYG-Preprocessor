<?php

namespace Tests;

use Akibatech\Wysiwyg\Modifier\AbsolutePath;
use Akibatech\Wysiwyg\Modifier\BbCode;
use Akibatech\Wysiwyg\Modifier\MailToLink;
use Akibatech\Wysiwyg\Modifier\NlToBr;
use Akibatech\Wysiwyg\Modifier\ParseVariables;
use Akibatech\Wysiwyg\Modifier\StripTags;
use Akibatech\Wysiwyg\Modifier\UrlToLink;
use PHPUnit\Framework\TestCase;
use Akibatech\Wysiwyg\Processor;

class DefaultModifiersTest extends TestCase
{
    /**
     * @test
     */
    public function testBbCodeModifier()
    {
        // Default options
        $input = '[b]Hello[/b] [color=red]world[/color]';
        $expected = '<strong>Hello</strong> <span style="color: red">world</span>';

        $processor = new Processor();
        $processor->addModifier(new BbCode());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input = '[strike]Hello[/strike]';
        $expected = '<span style="text-decoration: line-through">Hello</span>';

        $processor = new Processor();
        $processor->addModifier(new BbCode(['strike' => '<span style="text-decoration: line-through">$1</span>']));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testMailToLinkModifier()
    {
        $input = 'hi@company.com';
        $expected = '<a href="mailto:hi@company.com">hi@company.com</a>';

        $processor = new Processor();
        $processor->addModifier(new MailToLink());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testNlToBrModifier()
    {
        // Default options
        $input = "a\nb";
        $expected = 'a<br>b';

        $processor = new Processor();
        $processor->addModifier(new NlToBr());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input = "a\rb";
        $expected = 'a<br />b';

        $processor = new Processor();
        $processor->addModifier(new NlToBr(['search' => "\r", 'replace' => "<br />"]));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testStripTags()
    {
        // Default options
        $input = "<em>Hello</em>";
        $expected = 'Hello';

        $processor = new Processor();
        $processor->addModifier(new StripTags());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input = "<em>Hello</em>";
        $expected = '<em>Hello</em>';

        $processor = new Processor();
        $processor->addModifier(new StripTags(['allow' => '<em>']));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testUrlToLink()
    {
        // Default options
        $input = "https://www.github.com";
        $expected = '<a href="https://www.github.com">https://www.github.com</a>';

        $processor = new Processor();
        $processor->addModifier(new UrlToLink());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input = "https://www.github.com";
        $expected = '<a href="https://www.github.com" class="link" target="_blank">https://www.github.com</a>';

        $processor = new Processor();
        $processor->addModifier(new UrlToLink(['class' => 'link', 'target' => '_blank']));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testParseVariables()
    {
        // Default options
        $input = "Hello %name%, my email is %email%";
        $expected = 'Hello Joe, my email is mail@example.com';

        $processor = new Processor();
        $processor->addModifier(new ParseVariables(['accept' => ['name' => 'Joe', 'email' => 'mail@example.com']]));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom delimiter
        $input = "Hello #name#!";
        $expected = 'Hello Joe!';

        $processor = new Processor();
        $processor->addModifier(new ParseVariables(['accept' => ['name' => 'Joe'], 'in' => '#']));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testAbsolutePath()
    {
        // Default options
        $input = '<a href="../bonjour.html"></a> <img src=\'../../files/sea.jpg\' />';
        $expected = '<a href="/bonjour.html"></a> <img src="/files/sea.jpg" />';

        $processor = new Processor();
        $processor->addModifier(new AbsolutePath());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom prefix
        $input = '<a href="../bonjour.html"></a> <img src=\'../../files/sea.jpg\' />';
        $expected = '<a href="http://site.com/bonjour.html"></a> <img src="http://site.com/files/sea.jpg" />';

        $processor = new Processor();
        $processor->addModifier(new AbsolutePath(['prefix' => 'http://site.com/']));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }
}