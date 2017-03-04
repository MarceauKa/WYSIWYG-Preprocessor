<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class MailToLink
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class MailToLink extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $pattern = '(([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6}))';
        $output  = preg_replace($pattern, $this->getTag(), $input);

        if (!empty($this->options['at'])) {
            $output = str_replace('@', $this->options['at'], $output);
        }

        return $output;
    }

    /**
     * Build the link tag template.
     *
     * @param   void
     * @return  string
     */
    private function getTag()
    {
        $tpl = '<a href="mailto:%s">%s</a>';

        return vsprintf($tpl, [
            '$0',
            '$0'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            // Replace @ by an other char. Default to false.
            'at' => false // 'Wanted symbol or false to desactivate.
        ];
    }
}
