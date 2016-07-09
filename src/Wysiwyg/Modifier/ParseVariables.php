<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class ParseVariables
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class ParseVariables extends AbstractModifier
{
    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $keys = array_keys($this->getVariables());
        $delimiter = $this->options['in'];

        // There's no vars...
        if (empty($keys)) {
            return $input;
        }

        // Wrap $key in $delimiter
        foreach ($keys as &$key) {
            $key = $delimiter.$key.$delimiter;
        }

        $values = array_values($this->getVariables());

        $output = str_replace($keys, $values, $input);

        return $output;
    }

    //-------------------------------------------------------------------------

    /**
     * Set variables.
     * Ex: [
     *      'email' => 'email@example.com',
     *      'name'  => 'John Doe'
     * ]
     *
     * @param   array $vars
     * @return  self
     */
    public function withVariables(array $vars = [])
    {
        $this->options['accept'] = $vars;
    }

    //-------------------------------------------------------------------------

    /**
     * Returns configured variables.
     *
     * @param   void
     * @return  array
     */
    public function getVariables()
    {
        return $this->options['accept'];
    }

    //-------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            // Variables
            'accept' => [],
            // Default delimitator. Ex: %foo%
            'in' => '%'
        ];
    }

    //-------------------------------------------------------------------------
}