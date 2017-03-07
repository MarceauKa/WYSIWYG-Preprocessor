<?php

namespace Tests;

use Akibatech\Wysiwyg\Modifier\AbsolutePath;
use Akibatech\Wysiwyg\Modifier\BbCode;
use Akibatech\Wysiwyg\Modifier\EmptyParagraphs;
use Akibatech\Wysiwyg\Modifier\MailToLink;
use Akibatech\Wysiwyg\Modifier\NlToBr;
use Akibatech\Wysiwyg\Modifier\ParseVariables;
use Akibatech\Wysiwyg\Modifier\StripTags;
use Akibatech\Wysiwyg\Modifier\TreatTags;
use Akibatech\Wysiwyg\Modifier\UrlToLink;
use Akibatech\Wysiwyg\Modifier\WordsFilter;
use Akibatech\Wysiwyg\Modifier\YoutubeLinkToIframe;
use PHPUnit\Framework\TestCase;
use Akibatech\Wysiwyg\Processor;

class DefaultModifiersTest extends TestCase
{
    /**
     * @test
     */
    public function testCallableModifier()
    {
        $input    = 'PHP 4.3.0';
        $expected = 'CUC 4.3.0';

        $processor = new Processor();
        $processor->addModifier(function($input) {
             return str_rot13($input);
        })->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testBbCode()
    {
        // Default options
        $input    = '[b]Hello[/b] [color=red]world[/color]';
        $expected = '<strong>Hello</strong> <span style="color: red">world</span>';

        $processor = new Processor();
        $processor->addModifier(new BbCode());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input    = '[strike]Hello[/strike]';
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
        $input    = 'hi@company.com';
        $expected = '<a href="mailto:hi@company.com">hi@company.com</a>';

        $processor = new Processor();
        $processor->addModifier(new MailToLink());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testNlToBr()
    {
        // Default options
        $input    = "a\nb";
        $expected = 'a<br>b';

        $processor = new Processor();
        $processor->addModifier(new NlToBr());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input    = "a\rb";
        $expected = 'a<br />b';

        $processor = new Processor();
        $processor->addModifier(new NlToBr([
            'search'  => "\r",
            'replace' => "<br />"
        ]));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testStripTags()
    {
        // Default options
        $input    = "<em>Hello</em>";
        $expected = 'Hello';

        $processor = new Processor();
        $processor->addModifier(new StripTags());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input    = "<em>Hello</em>";
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
        $input    = "https://www.github.com";
        $expected = '<a href="https://www.github.com">https://www.github.com</a>';

        $processor = new Processor();
        $processor->addModifier(new UrlToLink());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom options
        $input    = "https://www.github.com";
        $expected = '<a href="https://www.github.com" class="link" target="_blank">https://www.github.com</a>';

        $processor = new Processor();
        $processor->addModifier(new UrlToLink([
            'class'  => 'link',
            'target' => '_blank'
        ]));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testParseVariables()
    {
        // Default options
        $input    = "Hello %name%, my email is %email%";
        $expected = 'Hello Joe, my email is mail@example.com';

        $processor = new Processor();
        $processor->addModifier(new ParseVariables([
            'accept' => [
                'name'  => 'Joe',
                'email' => 'mail@example.com'
            ]
        ]));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom delimiter
        $input    = "Hello #name#!";
        $expected = 'Hello Joe!';

        $processor = new Processor();
        $processor->addModifier(new ParseVariables([
            'accept' => ['name' => 'Joe'],
            'in'     => '#'
        ]));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testAbsolutePath()
    {
        // Default options
        $input    = '<a href="../bonjour.html"></a> <img src=\'../../files/sea.jpg\' />';
        $expected = '<a href="/bonjour.html"></a> <img src="/files/sea.jpg" />';

        $processor = new Processor();
        $processor->addModifier(new AbsolutePath());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);

        // Custom prefix
        $input    = '<a href="../bonjour.html"></a> <img src=\'../../files/sea.jpg\' />';
        $expected = '<a href="http://site.com/bonjour.html"></a> <img src="http://site.com/files/sea.jpg" />';

        $processor = new Processor();
        $processor->addModifier(new AbsolutePath(['prefix' => 'http://site.com/']));
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testWordsFilter()
    {
        // Default options
        $input    = 'Cunt!';
        $expected = '[censored]!';

        $modifier = new WordsFilter();
        $modifier->withWords(['cunt']);

        $processor = new Processor();
        $processor->addModifier($modifier);
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testEmptyParagraphs()
    {
        // Default options
        $input    = '<p>  </p><p> &nbsp; </p><p>&nbsp</p><p>Hello world</p>';
        $expected = '<p>Hello world</p>';

        $processor = new Processor();
        $processor->addModifier(new EmptyParagraphs());
        $processor->process($input);

        $this->assertEquals($processor->getOutput(), $expected);
    }

    /**
     * @test
     */
    public function testYoutubeLinkToIframe()
    {
        // Default options with long link
        $input = 'My new video: https://www.youtube.com/watch?v=6wlvYh0h63k !';
        $expected = 'My new video: <iframe src="https://www.youtube.com/embed/6wlvYh0h63k?controls=1&rel=0&showinfo=1" class="youtube-iframe" width="560" height="315" frameborder="0" allowfullscreen></iframe> !';

        $processor = (new Processor())->addModifier(new YoutubeLinkToIframe)->process($input);
        $this->assertEquals($expected, $processor->getOutput());

        // Default options with short link
        $input = 'New video here: https://youtu.be/wBqM2ytqHY4 !';
        $expected = 'New video here: <iframe src="https://www.youtube.com/embed/wBqM2ytqHY4?controls=1&rel=0&showinfo=1" class="youtube-iframe" width="560" height="315" frameborder="0" allowfullscreen></iframe> !';

        $processor = (new Processor())->addModifier(new YoutubeLinkToIframe)->process($input);
        $this->assertEquals($expected, $processor->getOutput());

        // With 2 videos
        $input = 'https://www.youtube.com/watch?v=6wlvYh0h63k and https://youtu.be/wBqM2ytqHY4';
        $expected = '<iframe src="https://www.youtube.com/embed/6wlvYh0h63k?controls=1&rel=0&showinfo=1" class="youtube-iframe" width="560" height="315" frameborder="0" allowfullscreen></iframe> and <iframe src="https://www.youtube.com/embed/wBqM2ytqHY4?controls=1&rel=0&showinfo=1" class="youtube-iframe" width="560" height="315" frameborder="0" allowfullscreen></iframe>';

        $processor = (new Processor())->addModifier(new YoutubeLinkToIframe)->process($input);
        $this->assertEquals($expected, $processor->getOutput());

        // With all options customized
        $input = 'https://youtu.be/6wlvYh0h63k';
        $expected = '<iframe src="https://www.youtube.com/embed/6wlvYh0h63k?controls=0&rel=1&showinfo=0" class="foo" frameborder="0"></iframe>';

        $modifier = new YoutubeLinkToIframe;
        $modifier->setOptions([
            'height'           => null,
            'width'            => null,
            'class'            => 'foo',
            'with_controls'    => false,
            'with_infos'       => false,
            'with_suggestions' => true,
            'allow_fullscreen' => false
        ]);

        $processor = (new Processor())->addModifier($modifier)->process($input);
        $this->assertEquals($expected, $processor->getOutput());
    }
}