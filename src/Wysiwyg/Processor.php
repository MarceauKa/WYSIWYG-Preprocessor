<?php

namespace Akibatech\Wysiwyg;

class Processor
{
    /**
     * @var ModifierInterface[]|callable[]
     */
    protected $modifiers;

    /**
     * @var string
     */
    protected $input = '';

    /**
     * @var string
     */
    protected $output = '';

    //-------------------------------------------------------------------------

    /**
     * Execute the processing.
     *
     * @param   string  $input
     * @return  self
     */
    public function process($input)
    {
        $this->input = $this->output = $input;

        $this->applyModifiers();

        return $this;
    }

    //-------------------------------------------------------------------------

    /**
     * Get the Processor input.
     *
     * @param   void
     * @return  string
     */
    public function getInput()
    {
        return $this->input;
    }

    //-------------------------------------------------------------------------

    /**
     * Get the processed input.
     *
     * @param   void
     * @return  string
     */
    public function getOutput()
    {
        return $this->output;
    }

    //-------------------------------------------------------------------------

    /**
     * Add a new modifier.
     *
     * @param   ModifierInterface|callable $modifier
     * @return  self
     */
    public function addModifier($modifier)
    {
        if (is_callable($modifier) OR $modifier instanceof ModifierInterface)
        {
            $this->modifiers[] = $modifier;
        }
        else
        {
            throw new \InvalidArgumentException('Modifier must be callable or an instance of Modifier interface.');
        }

        return $this;
    }

    //-------------------------------------------------------------------------

    /**
     * A many modifiers.
     *
     * @param   ModifierInterface[] $modifiers
     * @return  self
     */
    public function addModifiers(array $modifiers)
    {
        if (count($modifiers) > 0)
        {
            foreach ($modifiers as $modifier)
            {
                if ($modifier instanceof ModifierInterface) {
                    $this->addModifier($modifier);
                    continue;
                }

                throw new \InvalidArgumentException("Given modifier must implements ModifierInterface.");
            }
        }

        return $this;
    }

    //-------------------------------------------------------------------------

    /**
     * Access modifiers list.
     *
     * @param   void
     * @return  ModifierInterface[]
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }

    //-------------------------------------------------------------------------

    /**
     * Apply all modifiers on the input.
     *
     * @param   void
     * @return  self
     */
    private function applyModifiers()
    {
        // There's no modifiers.
        if (count($this->modifiers) === 0)
        {
            return $this;
        }

        // Loop over modifiers and handle them.
        foreach ($this->modifiers as $modifier)
        {
            // Modifier implements ModifierInterface
            if ($modifier instanceof ModifierInterface)
            {
                $this->output = $modifier->handle($this->output);
            }
            // Modifier is callable
            else if (is_callable($modifier))
            {
                $this->output = $modifier($this->output);
            }
        }

        return $this;
    }

    //-------------------------------------------------------------------------
}
