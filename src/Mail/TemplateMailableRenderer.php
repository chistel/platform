<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           TemplateMailableRenderer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 2:36 PM
 */

namespace Platform\Mail;

use Illuminate\Support\Str;
use Mustache_Engine;
use Platform\Contracts\Mail\MailTemplateInterface;
use Platform\Exceptions\Mail\CannotRenderTemplateMailable;

class TemplateMailableRenderer
{
    public const RENDER_HTML_LAYOUT = 0;
    public const RENDER_TEXT_LAYOUT = 1;

    /** @var TemplateMailable */
    protected TemplateMailable $templateMailable;

    /** @var MailTemplateInterface */
    protected MailTemplateInterface $mailTemplate;

    /** @var Mustache_Engine */
    protected Mustache_Engine $mustache;

    public function __construct(TemplateMailable $templateMailable, Mustache_Engine $mustache)
    {
        $this->templateMailable = $templateMailable;
        $this->mustache = $mustache;
        $this->mailTemplate = $templateMailable->getMailTemplate();
    }

    /**
     * @throws CannotRenderTemplateMailable
     */
    public function renderHtmlLayout(array $data = []): string
    {
        $body = $this->mustache->render(
            $this->mailTemplate->getHtmlTemplate(),
            $data
        );

        return $this->renderInLayout($body, static::RENDER_HTML_LAYOUT, $data);
    }

    /**
     * @throws CannotRenderTemplateMailable
     */
    public function renderTextLayout(array $data = []): ?string
    {
        if (! $this->mailTemplate->getTextTemplate()) {
            return $this->textView ?? null;
        }

        $body = $this->mustache->render(
            $this->mailTemplate->getTextTemplate(),
            $data
        );

        return $this->renderInLayout($body, static::RENDER_TEXT_LAYOUT, $data);
    }

    public function renderSubject(array $data = []): string
    {
        return $this->mustache->render(
            $this->mailTemplate->getSubject(),
            $data
        );
    }

    /**
     * @throws CannotRenderTemplateMailable
     */
    protected function renderInLayout(string $body, int $layoutType, array $data = []): string
    {
        $method = $layoutType === static::RENDER_HTML_LAYOUT ? 'getHtmlLayout' : 'getTextLayout';
        $layout = $this->templateMailable->$method()
            ?? (method_exists($this->mailTemplate, $method) ? $this->mailTemplate->$method() : null)
            ?? '{{{ body }}}';

        $this->guardAgainstInvalidLayout($layout);

        $data = array_merge(['body' => $body], $data);

        return $this->mustache->render($layout, $data);
    }

    /**
     * @throws CannotRenderTemplateMailable
     */
    protected function guardAgainstInvalidLayout(string $layout): void
    {
        if (! Str::contains($layout, [
            '{{{body}}}',
            '{{{ body }}}',
            '{{body}}',
            '{{ body }}',
            '{{ $body }}',
            '{!! $body !!}',
        ])) {
            throw CannotRenderTemplateMailable::layoutDoesNotContainABodyPlaceHolder($this->templateMailable);
        }
    }
}