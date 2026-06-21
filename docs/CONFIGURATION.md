# Configuration

Open **Setup -> Modules -> PagePdf** to configure the module.

## Library Status

Shows whether mPDF and Dompdf are installed. mPDF is recommended and installed by default with `composer install`; Dompdf is optional.

## Paper Settings

- **Paper Size:** A3, A4, A5, Letter, Legal, Ledger
- **Orientation:** Portrait or Landscape

## Margins

All margins are configured in millimeters:

- Top
- Right
- Bottom
- Left
- Header
- Footer

## Typography

- **Default Font:** Includes DejaVu Sans, Helvetica, Arial, Times, Courier, and related variants
- **Font Size:** 6-24 pt

Use DejaVu Sans for multilingual documents and Cyrillic text.

## Header and Footer

Headers and footers support these variables:

| Variable | Description | Example |
| --- | --- | --- |
| `{PAGENO}` | Current page number | 1 |
| `{nbpg}` | Total pages | 5 |
| `{DATE}` | Current date | 21.06.2026 |
| `{sitename}` | Site host | example.com |

Example footer:

```text
Page {PAGENO} of {nbpg} | {DATE} | {sitename}
```

## Images

- **Enable Images:** Include or strip `<img>` tags before rendering
- **Image DPI:** 72-300

Lower DPI keeps files smaller. Higher DPI improves print quality.

## Watermark

- Text
- Opacity from `0.0` to `1.0`

## Security

- Enable PDF protection
- Set user password
- Set owner password
- Restrict copy and print permissions

## Debug and Logging

- **Debug Mode:** Shows detailed errors on generation failure
- **Enable Logging:** Writes generation events to **Setup -> Logs -> pdf**

Production sites usually benefit from disabling logging once setup is stable.

## Default Configuration

```text
Paper Size: Letter
Orientation: Portrait
Margins: Top/Bottom 20mm, Left/Right 10mm
Header/Footer margins: 10mm
Font: Helvetica, 12pt
Footer: {PAGENO} / {nbpg}
Images: Enabled, 96 DPI
Logging: Enabled
```
