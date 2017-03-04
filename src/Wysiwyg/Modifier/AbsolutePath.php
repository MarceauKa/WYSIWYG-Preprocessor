<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class AbsolutePath
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class AbsolutePath extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $prefix  = $this->options['prefix'] ? $this->options['prefix'] : '/';
        $replace = '$1="'.$prefix.'$2"';
        $pattern = '/(href|src)\=(?:"|\'){1,}(?:\.{1,2}\/)+(\S*)(?:"|\'){1,}/';
        $output  = preg_replace($pattern, $replace, $input);

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            'prefix' => null
        ];
    }
}
