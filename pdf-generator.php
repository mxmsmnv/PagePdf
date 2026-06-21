<?php namespace ProcessWire;

// Get page ID from URL parameter
$pageId = $input->get->int('id');

if(!$pageId) {
    throw new Wire404Exception('Page ID not provided');
}

// Find the target page
$targetPage = $pages->get($pageId);

if(!$targetPage || !$targetPage->id) {
    throw new Wire404Exception('Page not found');
}

// Check access permissions
if(!$targetPage->viewable()) {
    throw new WirePermissionException('No access to this page');
}

// Build HTML with all fields
$html = buildCompleteHtml($targetPage);

// Clean external images before PDF generation
$html = cleanExternalImages($html);

// Generate PDF - module handles margins, header, footer from settings
$targetPage->toPdf([
    'html' => $html,
    'filename' => $sanitizer->pageName($targetPage->title) . '.pdf',
]);

exit();

/**
 * Remove external images that mPDF cannot access
 */
function cleanExternalImages($html) {
    $config = wire('config');
    $baseUrl = $config->httpHost;

    // Pattern to find all img tags
    $pattern = '/<img([^>]+)src=["\']([^"\']+)["\']([^>]*)>/i';

    $html = preg_replace_callback($pattern, function($matches) use ($baseUrl) {
        $fullTag = $matches[0];
        $src = $matches[2];

        // Check if image is external
        $isExternal = (
            strpos($src, 'http://') === 0 ||
            strpos($src, 'https://') === 0
        ) && strpos($src, $baseUrl) === false;

        if($isExternal) {
            // Remove external images
            return '<!-- External image removed: ' . htmlspecialchars($src) . ' -->';
        }

        return $fullTag;
    }, $html);

    return $html;
}

/**
 * Build complete HTML structure with all field types
 */
function buildCompleteHtml($page) {
    // Get module settings
    $pdfModule = wire('modules')->get('PagePdf');
    $fontSize = $pdfModule->font_size ?: 12;
    $fontFamily = $pdfModule->default_font ?: 'helvetica';

    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: ' . $fontFamily . ', sans-serif;
            font-size: ' . $fontSize . 'pt;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: ' . ($fontSize + 6) . 'pt;
            color: #1a1a1a;
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #0066cc;
        }

        h2 {
            font-size: ' . ($fontSize + 3) . 'pt;
            color: #0066cc;
            margin: 15px 0 6px 0;
        }

        h3 {
            font-size: ' . ($fontSize + 1) . 'pt;
            color: #444;
            margin: 12px 0 6px 0;
        }

        p, div {
            margin: 0 0 8px 0;
        }

        .field-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .summary-box {
            background: #f5f5f5;
            padding: 10px;
            border-left: 3px solid #0066cc;
            margin: 8px 0;
        }

        img {
            max-width: 48%;
            height: auto;
            margin: 3px;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: ' . ($fontSize - 1) . 'pt;
            page-break-inside: avoid;
        }

        table th {
            background: #0066cc;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: ' . ($fontSize - 1) . 'pt;
        }

        table td {
            border: 1px solid #ddd;
            padding: 4px 8px;
        }

        table tr:nth-child(even) {
            background: #fafafa;
        }

        .repeater-item {
            background: #fafafa;
            border-left: 2px solid #0066cc;
            padding: 8px 10px;
            margin: 6px 0;
            page-break-inside: avoid;
        }

        .repeater-item h4 {
            margin: 0 0 6px 0;
            font-size: ' . ($fontSize + 1) . 'pt;
            color: #0066cc;
        }

        .repeater-field {
            margin: 4px 0;
            font-size: ' . ($fontSize - 1) . 'pt;
        }

        .repeater-field strong {
            color: #555;
        }

        .combo-field {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            padding: 8px 10px;
            margin: 8px 0;
        }

        ul {
            margin: 6px 0;
            padding-left: 20px;
        }

        li {
            margin: 3px 0;
        }

        h1, h2, h3, h4 {
            page-break-after: avoid;
        }
    </style>
</head>
<body>';

    // Title
    $html .= '<h1>' . htmlspecialchars($page->title) . '</h1>';

    // Process all fields dynamically
    foreach($page->template->fields as $field) {
        $fieldName = $field->name;
        $value = $page->get($fieldName);

        // Skip system fields
        if(in_array($fieldName, ['id', 'parent', 'template', 'title', 'created', 'modified', 'status'])) {
            continue;
        }

        // Skip empty values
        if(empty($value)) continue;

        $fieldLabel = $field->label ?: $field->name;
        $fieldType = $field->type->name;

        $html .= '<div class="field-section">';

        // Handle different field types
        switch($fieldType) {

            case 'FieldtypeText':
                $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                $html .= '<div class="summary-box">' . htmlspecialchars($value) . '</div>';
                break;

            case 'FieldtypeTextarea':
            case 'FieldtypeTextareaLanguage':
            case 'FieldtypeCKEditor':
                $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                $html .= '<div>' . $value . '</div>';
                break;

            case 'FieldtypeImage':
                if(is_object($value)) {
                    $className = get_class($value);

                    if($className == 'ProcessWire\Pageimages') {
                        $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                        foreach($value as $img) {
                            $html .= '<img src="' . $img->httpUrl . '" alt="' . htmlspecialchars($img->description) . '">';
                        }
                    } elseif($className == 'ProcessWire\Pageimage') {
                        $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                        $html .= '<img src="' . $value->httpUrl . '" alt="' . htmlspecialchars($value->description) . '">';
                    }
                }
                break;

            case 'FieldtypeFile':
                if(is_object($value)) {
                    $className = get_class($value);

                    if($className == 'ProcessWire\Pagefiles') {
                        $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                        $html .= '<ul>';
                        foreach($value as $file) {
                            $html .= '<li>' . htmlspecialchars($file->basename) . ' (' . wireBytesStr($file->filesize) . ')</li>';
                        }
                        $html .= '</ul>';
                    }
                }
                break;

            case 'FieldtypePage':
                if(is_object($value) && isset($value->id) && $value->id) {
                    $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                    $html .= '<p>' . htmlspecialchars($value->title) . '</p>';
                } elseif(is_object($value)) {
                    $className = get_class($value);
                    if(strpos($className, 'PageArray') !== false) {
                        $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                        $html .= '<ul>';
                        foreach($value as $p) {
                            $html .= '<li>' . htmlspecialchars($p->title) . '</li>';
                        }
                        $html .= '</ul>';
                    }
                }
                break;

            case 'FieldtypeTable':
                if(is_object($value)) {
                    $className = get_class($value);
                    if(strpos($className, 'TableRows') !== false) {
                        $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                        $html .= '<table>';

                        $firstRow = $value->first();
                        if($firstRow) {
                            $html .= '<thead><tr>';
                            foreach($firstRow as $colName => $colValue) {
                                $html .= '<th>' . htmlspecialchars($colName) . '</th>';
                            }
                            $html .= '</tr></thead>';
                        }

                        $html .= '<tbody>';
                        foreach($value as $row) {
                            $html .= '<tr>';
                            foreach($row as $cellValue) {
                                if(is_array($cellValue)) {
                                    $html .= '<td>' . htmlspecialchars(implode(', ', $cellValue)) . '</td>';
                                } elseif(is_object($cellValue)) {
                                    $html .= '<td>' . htmlspecialchars((string)$cellValue) . '</td>';
                                } else {
                                    $html .= '<td>' . htmlspecialchars($cellValue) . '</td>';
                                }
                            }
                            $html .= '</tr>';
                        }
                        $html .= '</tbody></table>';
                    }
                }
                break;

            case 'FieldtypeRepeater':
            case 'FieldtypeRepeaterMatrix':
                if(is_object($value)) {
                    $className = get_class($value);
                    if(strpos($className, 'PageArray') !== false) {
                        $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';

                        $itemNum = 1;
                        foreach($value as $item) {
                            $html .= '<div class="repeater-item">';

                            if($fieldType == 'FieldtypeRepeaterMatrix' && $item->type) {
                                $html .= '<h4>#' . $itemNum . ' (' . $item->type . ')</h4>';
                            } else {
                                $html .= '<h4>#' . $itemNum . '</h4>';
                            }

                            foreach($item->template->fields as $subField) {
                                $subValue = $item->get($subField->name);

                                if(in_array($subField->name, ['id', 'parent', 'template'])) {
                                    continue;
                                }

                                if(empty($subValue)) continue;

                                $subLabel = $subField->label ?: $subField->name;

                                $html .= '<div class="repeater-field">';
                                $html .= '<strong>' . htmlspecialchars($subLabel) . ':</strong> ';

                                if($subField->type->name == 'FieldtypeImage' && is_object($subValue)) {
                                    $subClassName = get_class($subValue);

                                    if($subClassName == 'ProcessWire\Pageimages') {
                                        $html .= '<br>';
                                        foreach($subValue as $img) {
                                            $html .= '<img src="' . $img->httpUrl . '" style="max-width: 150px; margin: 3px;">';
                                        }
                                    } elseif($subClassName == 'ProcessWire\Pageimage') {
                                        $html .= '<br><img src="' . $subValue->httpUrl . '" style="max-width: 150px; margin: 3px;">';
                                    }
                                } elseif(is_string($subValue)) {
                                    $html .= htmlspecialchars($subValue);
                                } else {
                                    $html .= htmlspecialchars((string)$subValue);
                                }

                                $html .= '</div>';
                            }

                            $html .= '</div>';
                            $itemNum++;
                        }
                    }
                }
                break;

            case 'FieldtypeCombo':
                $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                $html .= '<div class="combo-field">';

                foreach($value as $key => $val) {
                    if(empty($val)) continue;

                    $html .= '<div class="repeater-field">';
                    $html .= '<strong>' . htmlspecialchars($key) . ':</strong> ';

                    if(is_object($val)) {
                        $valClass = get_class($val);
                        if(strpos($valClass, 'Array') !== false || strpos($valClass, 'WireArray') !== false) {
                            $html .= '<br>';
                            foreach($val as $item) {
                                $html .= htmlspecialchars((string)$item) . '<br>';
                            }
                        } else {
                            $html .= htmlspecialchars((string)$val);
                        }
                    } elseif(is_array($val)) {
                        $html .= htmlspecialchars(implode(', ', $val));
                    } else {
                        $html .= htmlspecialchars($val);
                    }

                    $html .= '</div>';
                }

                $html .= '</div>';
                break;

            case 'FieldtypeDatetime':
                if($value) {
                    $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                    $timestamp = is_numeric($value) ? (int)$value : strtotime($value);
                    if($timestamp) {
                        $html .= '<p>' . date('d.m.Y H:i', $timestamp) . '</p>';
                    } else {
                        $html .= '<p>' . htmlspecialchars($value) . '</p>';
                    }
                }
                break;

            case 'FieldtypeCheckbox':
                $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                $html .= '<p>' . ($value ? 'Yes' : 'No') . '</p>';
                break;

            case 'FieldtypeURL':
            case 'FieldtypeEmail':
                $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                $html .= '<p>' . htmlspecialchars($value) . '</p>';
                break;

            default:
                if(is_string($value) || is_numeric($value)) {
                    $html .= '<h2>' . htmlspecialchars($fieldLabel) . '</h2>';
                    $html .= '<p>' . nl2br(htmlspecialchars($value)) . '</p>';
                }
                break;
        }

        $html .= '</div>';
    }

    $html .= '</body></html>';

    return $html;
}