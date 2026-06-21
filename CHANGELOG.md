# Changelog

All notable changes to PagePdf will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [3.0.1] - 2026-06-21

### Changed
- Updated the README image to use the public `mxmsmnv/PagePdf` raw GitHub URL.
- Updated repository links and module metadata versions to `3.0.1`.

---

## [3.0.0] - 2026-06-21

### Breaking
- Renamed the module from `Pdf`/legacy `WirePDF` branding to `PagePdf`; use `$modules->get('PagePdf')` instead of `$modules->get('Pdf')`.
- Renamed the process module from `ProcessPdfWizard` to `ProcessPagePdfWizard`.

### Added
- `ProcessPagePdfWizard` admin wizard for checking setup status, saving common defaults, creating the generator template/page, and building a test PDF link.

### Changed
- Changed the cache/session namespace from `Pdf` to `PagePdf`.
- Shortened `README.md` into a quick-start overview and moved detailed documentation into `docs/`.
- Removed the duplicate root `INSTALL.md`; installation details now live in `docs/INSTALLATION.md`.
- Updated module metadata versions to `3.0.0`.

### Fixed
- Avoided legacy `WirePDF` / `Pages2Pdf` upgrade confusion and class-name collision risk by using a unique module name.

---

## [2.4.0] - 2026-02-26

### Fixed
- Typo in `composer.json` author email (`smnv.orog` → `smnv.org`)
- Version number mismatch: docblock said `2.1.0` while `getModuleInfo()` said `2.3.0` — both now set to `2.4.0`

---

## [2.3.0] - 2025-12-20

### Added
- PDF password protection and permission restrictions (`enable_protection`, `user_password`, `owner_password`)
- Watermark support with configurable text and opacity
- `Page::renderPdf()` hook for getting PDF output as string without sending to browser
- `savePdf()` public method for saving PDF directly to a file path
- Recursive rendering prevention via session flag to avoid infinite loops
- `{sitename}` and `{DATE}` variable support in header/footer text
- Detailed single-line log format: page title, ID, template, filename, paper, engine, execution time
- Debug mode with detailed error output including exception trace and library status
- Library status panel in module config showing installed/missing PDF libraries with install commands
- Dompdf support as alternative PDF engine
- Fallback autoload path detection: module vendor → root vendor → parent directories
- ProFields support: Table, Repeater, RepeaterMatrix, Combo
- 14 font options including DejaVu Sans family for full multilingual/Cyrillic support
- Header and footer with `{PAGENO}`, `{nbpg}`, `{DATE}`, `{sitename}` variables
- Image DPI setting (72–300)
- Paper size selection: A3, A4, A5, Letter, Legal, Ledger
- Portrait/Landscape orientation
- Configurable margins: top, right, bottom, left, header, footer
- `inline` option on `Page::toPdf()` for browser preview vs. download

### Changed
- Logging controlled by `enable_logging` toggle — can be disabled in production
- Log messages use ✓/✗ prefix for easy visual scanning
- `getPageHtml()` supports three modes: raw HTML string, custom template file, or page render
- mPDF config passes `tempDir` pointing to `site/assets/cache/Pdf/`
- Error display respects `debug_mode` flag
- Composer autoload checks module's own `vendor/` directory first (self-contained install)

### Fixed
- External images silently stripped instead of causing mPDF exceptions
- Cache directory created automatically on `ready()` if it doesn't exist

---

## Links

- GitHub: https://github.com/mxmsmnv/PagePdf
- Author: Maxim Semenov — maxim@smnv.org — smnv.org
