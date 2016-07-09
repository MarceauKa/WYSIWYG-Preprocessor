<?php

namespace Akibatech\Wysiwyg;

/**
 * Interface ModifierInterface
 *
 * @package Akibatech\Wysiwyg
 */
interface ModifierInterface
{
    //-------------------------------------------------------------------------

    /**
     * Execute the modifier.
     *
     * @param   string
     * @return  string
     */
    public function handle($input);

    //-------------------------------------------------------------------------
}