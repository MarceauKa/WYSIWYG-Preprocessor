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
        $table  = $this->getMatchesTable();
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
                $extra   = false;
            }
            else if (is_array($pattern))
            {
                $replace = $pattern[0];
                $extra   = true;
            }
            else
            {
                $replace = $pattern;
                $extra   = false;
            }

            $pattern = '/\[\s*%s\s*%s\s*\](.*)\[\/\s*%s\s*\]/is';
            $option  = '';

            if ($extra)
            {
                $option = '=?(.*)?';
            }

            $patterns[] = [
                sprintf($pattern, $tag, $option, $tag),
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
            'b'      => '<strong>$1</strong>',
            'i'      => '<em>$1</em>',
            'u'      => '<u>$1</u>',
            'left'   => '<p style="text-align: left">$1</p>',
            'right'  => '<p style="text-align: right">$1</p>',
            'center' => '<p style="text-align: center">$1</p>',
            'color'  => ['<span style="color: $1">$2</span>'],
            'quote'  => '<blockquote>$1</blockquote>',
            'size'   => ['<span style="font-size: $1px">$2</span>'],
            'link'   => ['<a href="$1">$2</a>'],
            'img'    => '<a href="$1"><img src="$1" /></a>'
        ];
    }

    //-------------------------------------------------------------------------
}