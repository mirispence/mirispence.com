<?php

use App\Services\MarkdownRenderer;

/*
 * Regression coverage for the league/commonmark XSS filter bypasses fixed in
 * 2.8.x–2.9.x (GHSA alerts #99, #130, #137): raw HTML must stay stripped and
 * unsafe link schemes must never reach an href/src, whatever bytes are mixed in.
 */

test('it renders safe markdown', function () {
    $html = (new MarkdownRenderer)->toHtml("**Bold** and [a link](https://example.com)\n\n![alt](https://example.com/a.png)");

    expect($html)
        ->toContain('<strong>Bold</strong>')
        ->toContain('<a href="https://example.com">a link</a>')
        ->toContain('<img src="https://example.com/a.png" alt="alt" />');
});

test('it returns an empty string for empty input', function (?string $markdown) {
    expect((new MarkdownRenderer)->toHtml($markdown))->toBe('');
})->with([null, '']);

test('it strips raw html and event handlers', function (string $markdown) {
    $html = (new MarkdownRenderer)->toHtml($markdown);

    expect($html)
        ->not->toMatch('/<\s*(script|img|svg|iframe|div|a|details|object|embed|style)\b/i')
        ->not->toMatch('/<[^>]*\son[a-z]+\s*=/i');
})->with([
    'script tag' => "Hello <script>alert('xss')</script>",
    'img onerror' => '<img src=x onerror=alert(1)>',
    'svg onload' => '<svg onload=alert(1)>',
    'iframe' => '<iframe src="https://evil.example"></iframe>',
    'block html with handler' => "<div onclick=\"alert(1)\">\nhi\n</div>",
    'form feed before handler' => "<img src=x\fonerror=alert(1)>",
    'tab before handler' => "<img src=x\tonerror=alert(1)>",
    'whitespace in tag name' => "<scr\tipt>alert(1)</scr\tipt>",
    'newline in tag name' => "<img\nsrc=x onerror=alert(1)>",
    'details ontoggle' => '<details open ontoggle=alert(1)>',
    'uppercase tag' => '<SCRIPT>alert(1)</SCRIPT>',
]);

test('it neutralises unsafe link and image urls', function (string $markdown) {
    $html = (new MarkdownRenderer)->toHtml($markdown);

    preg_match_all('/\s(?:href|src)="([^"]*)"/i', $html, $matches);

    foreach ($matches[1] as $url) {
        // Normalise the way a browser's URL parser does: decode entities, trim
        // leading/trailing C0 controls and spaces, drop tabs/newlines anywhere.
        // Percent-escapes are left alone — browsers never decode them in a scheme.
        $url = html_entity_decode($url, ENT_QUOTES | ENT_HTML5);
        $url = preg_replace('/[\t\n\r]/', '', trim($url, "\x00..\x20"));

        expect($url)->not->toMatch('/^(javascript|vbscript|data|file):/i');
    }

    expect($html)->not->toMatch('/<[^>]*\son[a-z]+\s*=/i');
})->with([
    'javascript link' => '[x](javascript:alert(1))',
    'uppercase scheme' => '[x](JaVaScRiPt:alert(1))',
    'vbscript link' => '[x](vbscript:msgbox(1))',
    'data html link' => '[x](data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==)',
    'file link' => '[x](file:///etc/passwd)',
    'javascript image' => '![x](javascript:alert(1))',
    'javascript autolink' => '<javascript:alert(1)>',
    'reference link' => "[x][r]\n\n[r]: javascript:alert(1)",
    'entity-encoded tab' => '[x](jav&#x09;ascript:alert(1))',
    'entity-encoded colon' => '[x](javascript&colon;alert(1))',
    'leading control byte' => "[x](\x01javascript:alert(1))",
    'embedded vertical tab' => "[x](java\x0Bscript:alert(1))",
    'embedded form feed' => "[x](java\fscript:alert(1))",
    'embedded null byte' => "[x](java\x00script:alert(1))",
    'percent-encoded scheme' => '[x](javascript%3Aalert(1))',
]);
