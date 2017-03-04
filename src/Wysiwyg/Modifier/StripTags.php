<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class StripTags
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class StripTags extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $output = strip_tags($input, $this->options['allow']);

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            'allow' => null,
        ];
    }
}
