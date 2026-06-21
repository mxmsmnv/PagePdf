# Troubleshooting

## Blank PDF

Possible causes:

- Complex CSS that mPDF cannot parse
- External resources that cannot be loaded
- Empty rendered page content

Try:

- Check **Setup -> Logs -> pdf**
- Enable Debug Mode in module settings
- Simplify HTML/CSS
- Remove external images

## Images Not Showing

Possible causes:

- External image URLs
- Incorrect file paths
- File permission issues
- Images disabled in module settings

Try:

- Use local ProcessWire images
- Check image paths and permissions
- Enable images in module settings
- Increase image DPI only when needed

## Memory Errors

Possible causes:

- Large PDFs
- Many high-resolution images

Try:

- Increase PHP memory limit
- Reduce image DPI
- Optimize images before upload
- Generate smaller PDFs

## Header/Footer Variables Not Working

Try:

- Use exact names: `{PAGENO}`, `{nbpg}`, `{DATE}`, `{sitename}`
- Enable header or footer in module settings
- Use variables in header/footer text, not body HTML

## Permission Errors

The cache directory must be writable:

```bash
chmod -R 775 /site/assets/cache/PagePdf/
```

## Excessive Logging

Try:

- Disable logging in module settings after setup
- Use ProcessWire log pruning in **Setup -> Logs -> Options**

## Generator Page Conflict

The wizard expects `/pdf/` to use the `pdf-generator` template. If `/pdf/` already exists with another template, rename that page or create the generator manually with another URL.
