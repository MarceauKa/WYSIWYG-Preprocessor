<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class UrlToLink
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class UrlToLink extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $pattern = '((http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?)';
        $output  = preg_replace($pattern, $this->getTag(), $input);

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
        $tpl    = '<a href="%s"%s%s>%s</a>';
        $class  = '';
        $target = '';

        if ($this->options['class'])
        {
            $class = ' class="' . $this->options['class'] . '"';
        }

        if ($this->options['target'])
        {
            $target = ' target="' . $this->options['target'] . '"';
        }

        return vsprintf($tpl, [
            '$0',
            $class,
            $target,
            '$0'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            'class'  => null,
            'target' => null
        ];
    }
}