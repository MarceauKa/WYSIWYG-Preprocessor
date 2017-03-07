# WYSIWYG Preprocessor

[![Build Status](https://travis-ci.org/MarceauKa/WYSIWYG-Preprocessor.svg?branch=master)](https://travis-ci.org/MarceauKa/WYSIWYG-Preprocessor)

WYSIWYG Preprocessor is **a PHP library with no dependencies**. It's a sort of **toolbox for processing your HTML textareas**.  

- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Customizing modifiers](#customizing-modifiers)
- [Modifiers](#modifiers)
    - [BBCode](#bbcode)
    - [Parse Variables](#parse-variables)
    - [Absolute Path](#absolute-path)
    - [Words Filter](#words-filter)
    - [Empty Paragraphs](#empty-paragraphs)
    - [Mail to Link](#mail-to-link)
    - [NlToBr](#nltobr)
    - [StripTags](#striptags)
    - [URL to Link](#url-to-link)
    - [Youtube Link to Iframe](#youtube-link-to-iframe)
- [Your own modifiers](#your-own-modifiers)
- [Unit Tests](#unit-tests)
- [Authors](#authors)

## Installation

Sources are managed with Composer.

```bash
composer require akibatech/wysiwygpreprocessor "0.*"
```

## Basic usage

For the given textarea, 

```php
$textarea = "Check my website http://website.com. Keep in touch at hello@website.com !";
```

We want to transform the link and the email adress to HTML tags

```php  
use Akibatech\Wysiwyg\Processor;  
use Akibatech\Wysiwyg\Modifier;  

$processor = new Processor();  

$processor->addModifier(new Modifier\UrlToLink)  
          ->addModifier(new Modifier\MailToLink)  
          ->process($textarea);  

echo $processor->getOutput();  
```

Results in :  

```html
Check my website <a href="http://website.com">http://website.com</a>. Keep in touch at <a href="mailto:hello@website.com">hello@website.com</a> !
```

## Customizing modifiers

Modifiers are easily customizable.  
Imagine you want to target all links to a new page or adding to it a custom class.  

```php  
$textarea = 'Check out my new site: personnal-website.com';

$modifier = new Akibatech\Wysiwyg\Modifier\UrlToLink();

$modifier->setOptions([
    'class' => 'custom-link',
    'target' => '_blank'
])

$processor = new Akibatech\Wysiwyg\Processor();

$processor->addModifier($modifier)
          ->process($textarea);

echo $processor->getOutput();
```

Results in :  

```html
Check out my new site: <a href="personnal-website.com" class="custom-link" target="_blank">personnal-website.com</a>
```

## Modifiers

### BBCode

Class: **Akibatech\Wysiwyg\Modifier\BbCode**  
Description: Apply a basic BBCode to enhance your content.  

Example input: ```[b]Hello[/b]```  
Example output: ```<strong>Hello</strong>```  

Options:  
Defaults tags are: b, i, u, left, right, center, quote, link, img, size and color.  
Options are wilcard BBCode tag. Key is the wanted BBCode tag and option is the HTML replacement.  
If pattern is given as array, it can access Tag option like ```[link=http://github.com]my profile[/link]``` as ```<a href="$1">$2</a>```.   
```php  
[  
    // New tag called [yellow]text in yellow[/yellow]  
    'yellow' => '<span style="color: yellow;">$1</span>',  
    // Disable default "b" tag  
    'b' => null  
]  
```

### Parse Variables

Class: **Akibatech\Wysiwyg\Modifier\ParseVariables**  
Description: Replace a preset of variables.  

Example input: ```Hello %name%!```  
Example output: ```Hello John!```  

Options:  
You can specify the delimiter and the accepted variables.
```php  
[  
    // My custom delimiter. Vars are parsed in this delimiter. Default is "%".  
    'in' => '%',  
    // Accepted vars
    'accept' => [
        'name' => 'Joe', // %name% => Joe
        'email' => 'email@example.com' // %email% => email@example.com
    ]
]  
```

### Absolute Path

Class: **Akibatech\Wysiwyg\Modifier\AbsolutePath**  
Description: Will replace "href" and "src" attributes with absolute values.  

Example input: ```<img src="../../files/sea.jpg" />```  
Example output: ```<img src="/files/sea.jpg" />```  

Options:  
You can specify a custom prefix for your paths.
```php  
[  
    // Custom prefix. Default is '/'.  
    'prefix' => 'http://site.com/', // <img src="http://site.com/files/sea.jpg" />
]  
```

### Words Filter

Class: **Akibatech\Wysiwyg\Modifier\WordsFilter**  
Description: Remove a words list from a text. Act as a censorship system.  

Example input: ```Cunt!```  
Example output: ```[censored]!```  

Options:  
The list and the replacement.
```php  
[  
    // Words list as an array.  
    'words' => ['word1', 'word2'], // No defaults words.
    // Replacement
    'replace' => '[censored]' // Wanted replacement, default to [censored]
]  
```

### Empty Paragraphs

Class: **Akibatech\Wysiwyg\Modifier\EmptyParagraphs**  
Description: Delete empty paragraphs from your content.  

Example input:  ```<p></p><p>Hello</p><p>&nbsp;</p>```  
Example output: ```<p>Hello</p>```  

Options:  
None.  

### Mail to Link

Class: **Akibatech\Wysiwyg\Modifier\MailToLink**  
Description: Transforms emails adresses in clickable link tag.  

Example input: ```email@company.com```  
Example output: ```<a href="mailto:email@company.com">email@company.com</a>```  

Options:    
```php  
[  
    // Will replace "@" by "<at>", set to false to disable...  
    'at' => '<at>',  
]  
```

### NlToBr

Class: **Akibatech\Wysiwyg\Modifier\NlToBr**  
Description: Replace line breaks into HTML line breaks. Similar to php native function nl2br().  

Example input: ```hello  
world```  
Example output: ```hello<br>world```  

Options:    
```php  
[  
    // Linebreak symbol to search. Defaults to "\n"  
    'search' => "\n",  
    // HTML to replace. Defaults to "<br>"  
    'replace' => '<br />'  
]  
```

### StripTags

Class: **Akibatech\Wysiwyg\Modifier\StripTags**  
Description: Remove HTML tags from input. Similar to php native function strip_tags().  

Example input: ```<p>hello world</p>```  
Example output: ```hello world```  

Options:    
```php  
[  
    // Allowed HTML tags (see strip_tags documentation). Defaults, none.  
    'allow' => "<a>",  
]  
```

### URL to Link

Class: **Akibatech\Wysiwyg\Modifier\UrlToLink**  
Description: Transforms web adresses in clickable link tag.  

Example input: ```https://www.github.com```  
Example output: ```<a href="https://www.github.com">https://www.github.com</a>```  

Options:    
```php  
[  
    // Add a custom class to all generated tags. No defaults.    
    'class' => 'link',  
    // Customize the link target. No defaults.  
    'target' => '_blank'  
]  
```

### Youtube Link to Iframe

Class: **Akibatech\Wysiwyg\Modifier\YoutubeLinkToIframe**  
Description: Transforms youtube links (long and shorts) to a embed video player (iframe).  

Example input: ```My new video: https://youtu.be/wBqM2ytqHY4```  
Example output: ```My new video: <iframe src="https://www.youtube.com/embed/wBqM2ytqHY4?controls=1&rel=0&showinfo=1" class="youtube-iframe" width="560" height="315" frameborder="0" allowfullscreen></iframe>```  

Options:    
```php  
[
    // Custom class added to the player
    'class'  => 'youtube-iframe',
    // Custom width (in px) or null
    'width'  => 560,
    // Custom height (in px) or null
    'height' => 315,
    // Allow fullscreen
    'allow_fullscreen' => true,
    // Enable youtube suggestions when video ends
    'with_suggestions' => false,
    // Display video info
    'with_infos' => true,
    // Display video controls
    'with_controls' => true
]
```

## Your own modifiers

You can easily extends the preprocessor by adding your own modifiers.  
All you need is to create a class implementing **ModifierInterface**. 
You're also encouraged to extends **AbstractModifier** to access common methods (setOptions, getOptions, ...).  

Basically, a modifier receive the input to transform through a public method **handle($input)**.  
Options are handled by a public method **defaultOptions()** returning an array of available options. And in your modifier body, you can access these options with the instance attribute **options**.

### Callable modifier

You also have the possibility to add a dynamic modifier.  
The method "addModifier" also accepts a callback function.  

Example :  
```php
$processor->addModifier(function($input) {
    return str_rot13('hello'); // Will return "uryyb"
});
```

## Unit Tests

WYSIWYG Preprocessor is tested with PHPUnit.  
Make sure you have composer dev dependencies installed and type :

```bash
vendor/bin/phpunit
```

## Authors

Author: [Marceau Casals](https://marceau.casals.fr) and [all contributors](https://github.com/MarceauKa/WYSIWYG-Preprocessor/graphs/contributors)  
Licence: [MIT](https://en.wikipedia.org/wiki/MIT_License)