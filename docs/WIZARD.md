# Setup Wizard

After installing the module, open **Setup -> PagePdf Wizard** in the ProcessWire admin.

The wizard provides four areas:

## Status

Shows whether these pieces are ready:

- PagePdf module
- mPDF
- Dompdf
- `pdf-generator.php` template file
- `pdf-generator` ProcessWire template
- `/pdf/` generator page

## Settings

Saves common defaults for the PagePdf module:

- PDF engine
- Paper size
- Orientation
- Default font
- Font size
- Image DPI
- Image rendering
- Footer rendering

For advanced options, use **Setup -> Modules -> PagePdf**.

## Generator

Creates or repairs the frontend PDF generator pieces:

- Copies `pdf-generator.php` to `/site/templates/pdf-generator.php`
- Creates the `pdf-generator` ProcessWire template
- Creates a hidden `/pdf/` page

The wizard will not overwrite an existing `/pdf/` page that uses another template.

## Test

Select any viewable page and build a test link:

```text
/pdf/?id=1234
```

Open the link to verify PDF output in the browser.
