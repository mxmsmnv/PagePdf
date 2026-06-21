# Installation

## Recommended: Composer in Module Directory

This keeps PDF dependencies contained inside the module folder.

1. Clone the repository into `/site/modules/`:

```bash
cd /site/modules
git clone https://github.com/mxmsmnv/WirePDF.git PagePdf
```

2. Install dependencies:

```bash
cd PagePdf
composer install
```

3. Install the module:

- Go to **Modules -> Refresh** in the ProcessWire admin
- Click **Install** next to **PagePdf**

4. Verify installation:

- Go to **Setup -> Modules -> PagePdf**
- Check the **Library Status** section
- mPDF should show as installed and ready

## Optional: Dompdf

If you want to use Dompdf as an alternative engine:

```bash
cd /path/to/processwire/site/modules/PagePdf
composer require dompdf/dompdf
```

Then select Dompdf in the module settings or pass `'engine' => 'dompdf'` in generation options.

## Upgrade Note for WirePDF and Pages2Pdf Users

PagePdf was previously published as WirePDF. The rename avoids collisions with the legacy `WirePDF` class bundled inside Pages2Pdf.

Do not install this module as an in-place upgrade for Pages2Pdf. Install it as a separate `PagePdf` module, verify PDF generation, and migrate templates/configuration deliberately.

## Legacy: Root Composer Install

You can install PDF dependencies in the ProcessWire root instead:

```bash
cd /path/to/processwire
composer require mpdf/mpdf
```

The module checks its own `vendor/` directory first, then falls back to the root vendor directory.

## Updating

1. Replace the module files with the new version.
2. Run Composer update in the module directory:

```bash
cd /site/modules/PagePdf
composer update
```

3. Refresh modules in ProcessWire admin.
4. Check [CHANGELOG.md](../CHANGELOG.md) for version-specific notes.

## Uninstalling

1. Uninstall the module in ProcessWire admin.
2. Delete `/site/modules/PagePdf/`.

Dependencies are removed with the module directory when using the recommended install.
