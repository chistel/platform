<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Markdown.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Html;

use HTMLPurifier;
use HTMLPurifier_Config;

class Markdown
{
    /**
     * Parse markdown string, outputting full paragraphs.
     *
     * @param string $markdown
     * @return string
     */
    public function parse(string $markdown): string
    {
        $html = $this->parsedown()
            ->setBreaksEnabled(true)
            ->text($markdown);

        return $this->sanitise($html);
    }

    /**
     * Parse markdown string, outputting as inline HTML without paragraphs.
     *
     * @param string $markdown
     * @return string
     */
    public function inline(string $markdown): string
    {
        return $this->sanitise($this->parsedown()->line($markdown));
    }

    /**
     * Parse markdown string, outputting PDF-friendly content.
     *
     * @param string $markdown
     * @return string
     */
    public function pdf(string $markdown): string
    {
        $html = $this->parsedown()
            ->setBreaksEnabled(true)
            ->embedVideos(false)
            ->text($markdown);

        return $this->sanitise($html);
    }

    protected function parsedown(): Parsedown
    {
        return (new Parsedown())->setMarkupEscaped(true);
    }

    private function sanitise(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();

        $storagePath = storage_path('app/purifier');

        if (!file_exists($storagePath)) {
            mkdir($storagePath);
        }

        $config->set('Cache.SerializerPath', $storagePath);
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.SafeIframe', true);
        $config->set(
            'URI.SafeIframeRegexp',
            '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'
        );

        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('a', 'data-redirector', 'Text');
        $def->addAttribute('iframe', 'allowfullscreen', 'Bool');

        return (new HTMLPurifier($config))->purify($html);
    }
}