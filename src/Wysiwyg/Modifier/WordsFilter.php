<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class WordsFilter
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class WordsFilter extends AbstractModifier
{
    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        // There's no words to filter
        if (count($this->options['words']) == 0)
        {
            return $input;
        }

        $output = str_ireplace($this->options['words'], $this->options['replace'], $input);

        return $output;
    }

    //-------------------------------------------------------------------------

    /**
     * Define a list of banned words.
     *
     * @param   array $words
     * @return  self
     */
    public function withWords(array $words = [])
    {
        $this->options['words'] = $words;

        return $this;
    }

    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            // List of banned words
            'words' => [],
            // Facultative replacer
            'replace' => '[censored]',
        ];
    }

    //-------------------------------------------------------------------------
}