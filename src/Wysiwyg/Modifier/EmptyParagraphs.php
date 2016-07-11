<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class EmptyParagraphs
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class EmptyParagraphs extends AbstractModifier
{
    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $output = preg_replace('/<p[^>]*>[\s|&nbsp;]*<\/p>/', '', $input);

        return $output;
    }

    //-------------------------------------------------------------------------
}