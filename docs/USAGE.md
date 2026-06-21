# Usage

## Generate a PDF from a Page

```php
$page->toPdf([
    'filename' => 'document.pdf'
]);
```

By default this sends the PDF as a download.

## Preview Inline

```php
$page->toPdf([
    'filename' => 'document.pdf',
    'inline' => true,
]);
```

## Custom Options

```php
$page->toPdf([
    'filename' => 'report.pdf',
    'paper_size' => 'A4',
    'orientation' => 'landscape',
    'margin_top' => 30,
    'margin_bottom' => 30,
]);
```

## Custom HTML

```php
$html = '<h1>Custom Report</h1><p>Content here...</p>';

$page->toPdf([
    'html' => $html,
    'filename' => 'custom-report.pdf',
]);
```

## Custom Template

Template paths are relative to `/site/templates/`.

```php
$page->toPdf([
    'template' => 'pdf-invoice.php',
    'filename' => 'invoice.pdf',
]);
```

## Render PDF as a String

```php
$pdf = $page->renderPdf([
    'paper_size' => 'A4',
]);
```

## Save PDF to a File

```php
$pdfModule = $modules->get('PagePdf');

$pdfModule->savePdf(
    $page,
    '/path/to/document.pdf',
    ['paper_size' => 'Letter']
);
```

## URL Generator

When using the included generator template and `/pdf/` page:

```text
https://yoursite.com/pdf/?id=1234
```

`1234` is the ID of the page you want to convert.

## Supported Field Types in the Generator

Native fields:

- Text
- Textarea
- CKEditor
- Image
- File
- Page
- Datetime
- Checkbox
- URL
- Email

ProFields:

- Table
- Repeater
- RepeaterMatrix
- Combo
