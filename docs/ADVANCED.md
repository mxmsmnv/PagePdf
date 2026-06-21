# Advanced Customization

## Custom PDF Generator

Create a template such as `/site/templates/pdf-report.php`:

```php
<?php namespace ProcessWire;

$pageId = $input->get->int('id');
$targetPage = $pages->get($pageId);

if(!$targetPage->id || !$targetPage->viewable()) {
    throw new Wire404Exception();
}

$html = '<h1>' . $sanitizer->entities($targetPage->title) . '</h1>';
$html .= '<p>' . $targetPage->body . '</p>';

$targetPage->toPdf([
    'html' => $html,
    'filename' => 'report-' . $targetPage->name . '.pdf',
    'paper_size' => 'A4',
    'watermark' => 'CONFIDENTIAL',
]);
```

## Engine Selection

```php
$page->toPdf([
    'engine' => 'mpdf',
]);
```

```php
$page->toPdf([
    'engine' => 'dompdf',
]);
```

## Password Protection

```php
$page->toPdf([
    'enable_protection' => true,
    'user_password' => 'reader-password',
    'owner_password' => 'owner-password',
]);
```

## Hook Example

The current generation methods are internal implementation methods. For custom behavior, prefer preparing HTML before calling `toPdf()`:

```php
$html = $files->render('pdf/custom-report.php', [
    'page' => $page,
]);

$page->toPdf([
    'html' => $html,
    'filename' => 'custom-report.pdf',
]);
```

## Performance Tips

- Compress images before upload
- Use 96 DPI for web PDFs
- Use 150 DPI or higher only for print PDFs
- Keep PDF CSS simple
- Cache generated PDFs when documents do not change often
- Disable logging in production when stable

## Security Notes

- Protect generator templates with ProcessWire access checks
- Use password protection for sensitive documents
- Validate all request input
- Avoid rendering untrusted HTML
- Review logs during setup and after failures
