<?php

namespace Akibatech\Wysiwyg;

/**
 * Class BaseModifier
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
abstract class AbstractModifier implements ModifierInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * BaseModifier constructor.
     *
     * @param   array $options
     * @return  self
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function handle($input);

    /**
     * Modifier default options.
     *
     * @param   void
     * @return  array
     */
    public function defaultOptions()
    {
        return [];
    }

    /**
     * Options setter.
     *
     * @param   array $options
     * @return  self
     */
    public function setOptions(array $options = [])
    {
        $this->options = array_merge($this->defaultOptions(), $options);

        return $this;
    }

    /**
     * Options getter.
     *
     * @param   void
     * @return  array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
