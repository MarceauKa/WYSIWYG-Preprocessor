<?php

namespace Akibatech\Wysiwyg\Modifier;

use Akibatech\Wysiwyg\AbstractModifier;

/**
 * Class YoutubeLinkToIframe
 *
 * @package Akibatech\Wysiwyg\Modifier
 */
class YoutubeLinkToIframe extends AbstractModifier
{
    /** @var string */
    const INTEGRATION_URL = 'https://www.youtube.com/embed/';

    /** @var string */
    const PARAM_CONTROLS = 'controls';

    /** @var string */
    const PARAM_SUGGESTIONS = 'rel';

    /** @var string */
    const PARAM_INFOS = 'showinfo';

    /** @var string */
    const REGEX = '#http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?#u';

    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $output = preg_replace_callback(self::REGEX, [$this, 'transform'], $input);

        return $output;
    }

    /**
     *
     */
    public function transform($matches)
    {
        $id = $matches[1];

        // Build the query
        $query = http_build_query([
            self::PARAM_CONTROLS => (bool)$this->options['with_controls'] ? 1 : 0,
            self::PARAM_SUGGESTIONS => (bool)$this->options['with_suggestions'] ? 1 : 0,
            self::PARAM_INFOS => (bool)$this->options['with_infos'] ? 1 : 0,
        ]);

        // Build the URL
        $url = self::INTEGRATION_URL . $id . '?' . $query;

        return vsprintf('<iframe %s%s%s%sframeborder="0"%s></iframe>', [
            "src=\"$url\" ",
            ! is_null($this->options['class']) ? "class=\"{$this->options['class']}\" " : '',
            ! is_null($this->options['width']) ? "width=\"{$this->options['width']}\" " : '',
            ! is_null($this->options['height']) ? "height=\"{$this->options['height']}\" " : '',
            (bool) $this->options['allow_fullscreen'] ? ' allowfullscreen' : '',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function defaultOptions()
    {
        return [
            // Custom class added to the player
            'class'  => 'youtube-iframe',
            // Custom width (in px) or null
            'width'  => 560,
            // Custom height (in px) or null
            'height' => 315,
            // Allow fullscreen
            'allow_fullscreen' => true,
            // Enable youtube suggestions when video ends
            'with_suggestions' => false,
            // Display video info
            'with_infos' => true,
            // Display video controls
            'with_controls' => true
        ];
    }
}
