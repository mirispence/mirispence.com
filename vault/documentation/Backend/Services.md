# Services

Tags: #backend #services

## `MarkdownRenderer`

File: `app/Services/MarkdownRenderer.php`

A thin wrapper around `league/commonmark`'s `GithubFlavoredMarkdownConverter`. Resolved from the container wherever needed.

### Configuration

```php
new GithubFlavoredMarkdownConverter([
    'html_input'         => 'strip',     // Raw HTML in Markdown is stripped (XSS protection)
    'allow_unsafe_links' => false,       // javascript: / data: links are blocked
]);
```

### API

```php
public function toHtml(?string $markdown): string
```

Returns an empty string for `null` or empty input. Otherwise returns the converted HTML string.

### Usage

Called as an appended attribute on models:

- `Artwork::description_html` — `$this->description`
- `Gallery::description_html` — `$this->description`
- `Book::description_html` — `$this->description`
- `Chapter::body_html` — `$this->body_markdown`

Resolved via `app(\App\Services\MarkdownRenderer::class)` — not injected in constructors — so it is lazily instantiated per request.

### Security Properties

- HTML tags embedded in Markdown input are stripped entirely (not escaped)
- Unsafe link schemes (`javascript:`, `data:`, `vbscript:`) are blocked
- The output is safe to render with `v-html` in Vue

---

## Related

- [[../Domains/Book & Chapter]]
- [[../Domains/Artwork]]
