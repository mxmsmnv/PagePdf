# PagePdf

A ProcessWire module for generating PDF documents from pages with mPDF or Dompdf. It supports UTF-8/Cyrillic text, configurable typography, headers, footers, watermarks, password protection, and common ProcessWire field types including ProFields.

![PagePdf](assets/PagePdf.png)

**Version:** 3.0.0<br>
**Author:** Maxim Semenov<br>
**Website:** [smnv.org](https://smnv.org)<br>
**Email:** [maxim@smnv.org](mailto:maxim@smnv.org)

If this project helps your work, consider supporting future development: [GitHub Sponsors](https://github.com/sponsors/mxmsmnv) or [smnv.org/sponsor](https://smnv.org/sponsor/).

Formerly published as WirePDF. The module was renamed to PagePdf in 3.0.0 to avoid confusion and upgrade collisions with the legacy `WirePDF` class bundled in Pages2Pdf.

## Features

- Convert ProcessWire pages to PDF with `$page->toPdf()`
- Render PDF output as a string with `$page->renderPdf()`
- Save PDFs directly to disk with `$modules->get('PagePdf')->savePdf()`
- Use mPDF by default, with Dompdf available as an optional engine
- Configure paper size, orientation, margins, font, image DPI, headers, footers, and watermarks
- Add PDF password protection and copy/print restrictions
- Use the included **Setup -> PagePdf Wizard** admin page for guided setup
- Generate PDFs from raw HTML, a custom template file, or full page rendering

## Requirements

- ProcessWire 3.0 or later
- PHP 8.0 or later
- Composer
- mPDF library, installed with `composer install`

## Quick Start

1. Clone the module into `/site/modules/PagePdf/`.
2. Run `composer install` inside the module directory.
3. In ProcessWire admin, go to **Modules -> Refresh**.
4. Install **PagePdf**.
5. Open **Setup -> PagePdf Wizard** and create the generator template/page.
6. Generate a PDF:

```php
$page->toPdf([
    'filename' => 'document.pdf'
]);
```

## Documentation

- [Installation](docs/INSTALLATION.md)
- [Setup Wizard](docs/WIZARD.md)
- [Configuration](docs/CONFIGURATION.md)
- [Usage](docs/USAGE.md)
- [Troubleshooting](docs/TROUBLESHOOTING.md)
- [Advanced Customization](docs/ADVANCED.md)
- [Changelog](CHANGELOG.md)

## Basic Example

```php
$page->toPdf([
    'filename' => 'report.pdf',
    'paper_size' => 'A4',
    'orientation' => 'landscape',
]);
```

## License

MIT License - free to use in commercial and non-commercial projects.

## Credits

- **Author:** Maxim Semenov — maxim@smnv.org — smnv.org
- **mPDF:** https://mpdf.github.io/
- **ProcessWire:** https://processwire.com/
