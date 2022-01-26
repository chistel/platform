<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Parsedown.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 9:07 AM
 */

namespace Platform\Html;

use Parsedown as OriginalParsedown;

class Parsedown extends OriginalParsedown
{
    /** @var bool */
    protected bool $embedVideos = true;

    /** @var int|null */
    private ?int $cacheExpiry;

    /** @var array */
    protected $videoServices = [
        'youtube' => [
            'regex' => "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
            'embed_url' => 'https://www.youtube.com/embed/',
            'matches_pos' => 1
        ],

        'vimeo' => [
            'regex' => "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/",
            'embed_url' => 'https://player.vimeo.com/video/',
            'matches_pos' => 5
        ]
    ];

    public function __construct()
    {
        $this->BlockTypes['!'][] = 'PlatformField';
    }

    /**
     * @param int $expiry
     * @return $this
     */
    public function setCacheExpiry(int $expiry): static
    {
        $this->cacheExpiry = $expiry;

        return $this;
    }

    /**
     * @param bool $embedVideos
     * @return Parsedown
     */
    public function embedVideos($embedVideos): static
    {
        $this->embedVideos = $embedVideos;

        return $this;
    }

    /**
     * Modify the regular expression to match URLs with a hyphen at the end.
     *
     * Compared to the original Parsedown class '-?' has been added after
     * the closing '\b' word boundary to optionally match the '-' character.
     *
     * @link https://regex101.com/r/eqHuca/1
     *
     * @param $Excerpt
     * @return array|void
     */
    protected function inlineUrl($Excerpt)
    {
        if ($this->urlsLinked !== true or !isset($Excerpt['text'][2]) or $Excerpt['text'][2] !== '/') {
            return;
        }

        if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b-?\/*/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            $url = $matches[0][0];

            return [
                'extent' => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element' => [
                    'name' => 'a',
                    'text' => $url,
                    'attributes' => [
                        'href' => $url,
                    ],
                ],
            ];
        }
    }

    /**
     * Adds "target" attribute to force links to open in a new tab
     */
    protected function inlineLink($excerpt)
    {
        $link = parent::inlineLink($excerpt);

        if (!$link || str_contains($excerpt['text'], '] (')) {
            return null;
        }

        $url = $link['element']['attributes']['href'];
        $link['element']['attributes']['data-redirector'] = external_url($url, $this->cacheExpiry);

        return $link;
    }

    /**
     * Prepares element to be rendered as a video if the src attribute can be matched to a video service
     */
    protected function inlineImage($excerpt)
    {
        $image = parent::inlineImage($excerpt);

        if ($this->isVideo($image['element']['attributes']['src'])) {
            $image['element']['name'] = 'vid';
            $image['element']['attributes']['width'] = $image['element']['attributes']['alt'];
        } elseif (!is_null($image)) {
            $image['element']['attributes']['class'] = 'markdown-img';
        }

        return $image;
    }

    /**
     * Catches video elements and overrides markup creation
     */
    protected function element(array $element)
    {
        if (isset($element['name']) && $element['name'] == 'vid') {
            return $this->embedVideos
                ? $this->embedVideo($element)
                : $this->linkToVideo($element);
        }

        return parent::element($element);
    }

    /**
     * Checks if url can be matched to a video service
     */
    private function isVideo($url): bool
    {
        foreach ($this->videoServices as $service) {
            if (preg_match($service['regex'], $url, $matches)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Creates embed markup for videos
     */
    private function embedVideo($element)
    {
        foreach ($this->videoServices as $service) {
            if (preg_match($service['regex'], $element['attributes']['src'], $matches)) {
                $url = $service['embed_url'] . $matches[$service['matches_pos']];
                $width = $element['attributes']['width'] ?? 0;
                $dimensions = '';

                if ($width && is_numeric($width)) {
                    $height = 9 * $width / 16;
                    $dimensions = 'width="' . $width . '" height="' . $height . '"';
                }

                return '<iframe src="' . $url . '" frameborder="0" ' . $dimensions . ' allowfullscreen></iframe>';
            }
        }
    }

    /**
     * Returns a link to video
     */
    private function linkToVideo($element): string
    {
        return '<a href="' . $element['attributes']['src'] . '">' . $element['attributes']['src'] . '</a>';
    }

    protected function blockPlatformField($line, $block)
    {
        if (preg_match('/<platform-field>([\s\S]*)/', $line['text'], $matches)) {
            return [
                'element' => [
                    'name' => 'platform-field',
                    'rawHtml' => $matches[1],
                ],
            ];
        }
    }

    protected function blockPlatformFieldContinue($line, $block)
    {
        if (isset($block['complete'])) {
            return;
        }

        if (isset($block['interrupted'])) {
            unset($block['interrupted']);
        }

        if (preg_match('/([\s\S]*?)<\/platform-field>/', $line['text'], $matches)) {
            $block['element']['rawHtml'] .= $matches[1];
            $block['complete'] = true;

            return $block;
        }

        $block['element']['rawHtml'] .= $line['body'];

        return $block;
    }

    protected function blockPlatformFieldComplete($block)
    {
        return $block;
    }
}