<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           TemplateMailable.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use Platform\Contracts\Mail\MailTemplateInterface;
use Platform\Exceptions\Mail\CannotRenderTemplateMailable;
use Platform\Database\Eloquent\Models\Mail\MailTemplate;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class TemplateMailable extends Mailable
{
    protected static string $templateModelClass = MailTemplate::class;

    /** @var MailTemplateInterface */
    protected MailTemplateInterface $mailTemplate;

    public static function getVariables(): array
    {
        return static::getPublicProperties();
    }

    public function getMailTemplate(): MailTemplateInterface
    {
        return $this->mailTemplate ?? $this->resolveTemplateModel();
    }

    protected function resolveTemplateModel(): MailTemplateInterface
    {
        return $this->mailTemplate = static::$templateModelClass::findForMailable($this);
    }

    /**
     * @throws ReflectionException
     * @throws CannotRenderTemplateMailable
     */
    protected function buildView(): array|string
    {
        $renderer = $this->getMailTemplateRenderer();

        $viewData = $this->buildViewData();

        $html = $renderer->renderHtmlLayout($viewData);
        $text = $renderer->renderTextLayout($viewData);

        return array_filter([
            'html' => new HtmlString($html),
            'text' => new HtmlString($text),
        ]);
    }

    /**
     * @throws ReflectionException
     */
    protected function buildSubject($message): TemplateMailable|static
    {
        if ($this->subject) {
            $message->subject($this->subject);

            return $this;
        }

        if ($this->getMailTemplate()->getSubject()) {
            $subject = $this
                ->getMailTemplateRenderer()
                ->renderSubject($this->buildViewData());

            $message->subject($subject);

            return $this;
        }

        return parent::buildSubject($message);
    }

    public function getHtmlLayout(): ?string
    {
        return null;
    }

    public function getTextLayout(): ?string
    {
        return null;
    }

    public function build(): static
    {
        return $this;
    }

    protected static function getPublicProperties(): array
    {
        $class = new ReflectionClass(static::class);

        return collect($class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map->getName()
            ->diff(static::getIgnoredPublicProperties())
            ->values()
            ->all();
    }

    protected static function getIgnoredPublicProperties(): array
    {
        $mailableClass = new ReflectionClass(Mailable::class);
        $queueableClass = new ReflectionClass(Queueable::class);

        return collect()
            ->merge($mailableClass->getProperties(ReflectionProperty::IS_PUBLIC))
            ->merge($queueableClass->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map->getName()
            ->values()
            ->all();
    }

    protected function getMailTemplateRenderer(): TemplateMailableRenderer
    {
        return app(TemplateMailableRenderer::class, ['templateMailable' => $this]);
    }
}
