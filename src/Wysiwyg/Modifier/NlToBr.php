<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class NlToBr
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class NlToBr extends AbstractModifier
{
    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $output = str_replace($this->options['search'], $this->options['replace'], $input);

        return $output;
    }

    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            'search'  => "\n",
            'replace' => '<br>'
        ];
    }

    //-------------------------------------------------------------------------
}