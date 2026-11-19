# T3Masonry

Five image gallery layouts for the content elements **image**, **textpic**, **media** and
**textmedia** of bootstrap_package: masonry, captions below, justified rows, spotlight and
essential. CSS only; a click opens the **lightbox of bootstrap_package** (PhotoSwipe), already
loaded by the bootstrap_package TypoScript. No JavaScript of its own, no extra requests on pages
that do not use the layouts.

| | |
|---|---|
| TYPO3 | 12.4 LTS, 13.4 LTS, 14.3 LTS |
| PHP | 8.1+ (as required by the TYPO3 version) |
| bootstrap_package | 14, 15 or 16 (Bootstrap 5) |

## Layouts

Choose the layout in the content element, tab *Appearance > Layout*:

| Layout | Name | Look |
|---|---|---|
| 2950 | T3Masonry - Classic | columns of different heights; zoom and caption on hover |
| 2951 | T3Masonry - Captions below | same layout, title and description always visible below each image |
| 2952 | T3Masonry - Justified rows | rows of the same height filling the width, nothing cropped (Flickr style) |
| 2953 | T3Masonry - Spotlight | compact; the image under the pointer stays in colour, the others turn grey |
| 2954 | T3Masonry - Essential | only the photos, square corners, no text on the images; title and description in the lightbox |

Fields used per image (file reference): **title**, **description**, **alternative text**
(falls back to the title), **link** (if set, a click opens the link instead of the lightbox),
the **crop** of the editor (variant "default": the gallery uses the cropped proportions).
Only image files are shown. Titles and descriptions are output as plain text.

Columns: field *Number of columns* of the content element (1–6, default 4); on tablets at most 3,
on phones the setting `mobileColumns` (2). Justified rows ignore it and use `rowHeight`.

### How it works

* TYPO3 knows the size of every image (after the crop). The gallery passes the proportions to the
  stylesheet (`--w` / `--h`), so the browser places every image **before it is loaded**: no
  jumping layout, no JavaScript.
* Masonry layouts: CSS grid where every image spans rows in proportion to its height
  (order left to right). Browsers without container queries get CSS columns instead
  (order top to bottom).
* Images: one `srcset` with two sizes (`imageMaxWidth` and half of it) and `sizes` based on the
  columns, lazy loading, explicit width/height.
* Lightbox: the markup of bootstrap_package (`bk2k:link.lightbox`), one group per content element,
  with title and description below the image, swipe, keyboard and zoom.
* The ViewHelper `t3m:imageInfo` only calculates (crop area, widths): no image processing, no
  database query.

### Accessibility

* Every image is a link or a lightbox button with a visible focus ring; hover effects also on
  keyboard focus.
* Touch screens: captions of the classic layout are always visible.
* With "reduce motion" enabled in the operating system the animations are switched off.

## Installation

Composer:

    composer require specialistaweb/t3masonry

Classic mode: upload the zip in *Admin Tools > Extensions*.

Then activate it for the site:

* **TYPO3 13 / 14 – site set (recommended):** *Site Management > Sites > edit site > Sets*, add
  **T3Masonry** (it depends on "Bootstrap Package: Full Package", which
  loads the lightbox).
* **TYPO3 12, or sites with TypoScript records:** add the static template
  **T3Masonry** after the bootstrap_package TypoScript.

Finally flush all caches.

## Configuration

With the site set, every option is in *Site Management > Settings*, category
**T3Masonry**. With a TypoScript record, the same options are constants
(Constant Editor, category *bootstrap package: t3masonry*).

| Option (`plugin.tx_t3masonry.settings.*`) | Default | Meaning |
|---|---|---|
| `forceLightbox` | 1 | 1 = lightbox on every image; 0 = only with "Enlarge on click" of the content element |
| `imageMaxWidth` | 900 | largest width delivered in the gallery (half size added for small screens), 0 = original |
| `cssFile` | `EXT:t3masonry/Resources/Public/Css/masonry.css` | stylesheet; change it only to use your own copy |
| `accentColor` | `#3d5afe` | focus ring, title badge of the spotlight layout |
| `textColor` | `#12141a` | titles of the "captions below" layout |
| `mutedColor` | `#5d6472` | descriptions of the "captions below" layout |
| `placeholderColor` | `#e9ecf1` | background while an image is loading |
| `gap` | 16 | space between the images in px (classic, captions below, justified) |
| `compactGap` | 10 | space between the images in px (spotlight, essential) |
| `radius` | 14 | corner radius in px (half for spotlight; essential is always square) |
| `rowHeight` | 240 | row height of the justified rows in px (60% on phones) |
| `mobileColumns` | 2 | columns on phones: 1 or 2 |

Size and quality of the lightbox images are the bootstrap_package settings
`plugin.bootstrap_package_contentelements.lightbox.image.maxWidth / maxHeight`.

### Performance

* The stylesheet (about 9 KB, 3 KB compressed) and the variables are added only to pages that
  contain a T3Masonry layout, once per page.
* Animations use only `transform`, `opacity` and `filter`.

## License

GPL-2.0-or-later, see LICENSE.
