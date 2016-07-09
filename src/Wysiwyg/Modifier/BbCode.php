<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class BbCode
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class BbCode extends AbstractModifier
{
    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $table = $this->getMatchesTable();
        $output = $input;

        foreach ($table as $match)
        {
            $output = preg_replace($match[0], $match[1], $output);
        }

        return $output;
    }

    //-------------------------------------------------------------------------

    /**
     * Returns the matches table.
     *
     * @param   void
     * @return  array
     */
    public function getMatchesTable()
    {
        $patterns = [];

        foreach ($this->options as $tag => $pattern)
        {
            // Delete BBCode if tag is not recognized.
            if (is_null($pattern))
            {
                $replace = '$1';
                $options = [];
            }
            else if (is_array($pattern))
            {
                $replace = $pattern[0];
                $options = $pattern[1];
            }
            else
            {
                $replace = $pattern;
                $options = [];
            }

            $pattern = '/\[\s*%s%s\s*\](.*)\[\/\s*%s\s*\]/is';
            $extra   = '';

            foreach ($options as $key => $option)
            {
                $extra .= '\s*'.$option.'=[\'|\"](.*)[\'|\"]\s*';
            }

            $patterns[] = [
                sprintf($pattern, $tag, $extra, $tag),
                $replace
            ];
        }

        return $patterns;
    }

    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            'b'    => '<strong>$1</strong>',
            'i'    => '<em>$1</em>',
            'u'    => '<u>$1</u>',
            'link' => ['<a href="$1">$2</a>', ['url']],
        ];
    }

    //-------------------------------------------------------------------------
}