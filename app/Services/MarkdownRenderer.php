<?php

namespace App\Services;

use League\CommonMark\GithubFlavoredMarkdownConverter;

class MarkdownRenderer
{
    protected GithubFlavoredMarkdownConverter $converter;

    public function __construct()
    {
        $this->converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * Convert markdown to safe HTML.
     */
    public function toHtml(?string $markdown): string
    {
        if (empty($markdown)) {
            return '';
        }

        return (string) $this->converter->convert($markdown);
    }
}
