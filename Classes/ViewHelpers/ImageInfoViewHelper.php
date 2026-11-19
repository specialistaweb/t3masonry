<?php

declare(strict_types=1);

namespace SpecialistaWeb\T3Masonry\ViewHelpers;

use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns what the masonry layouts need to place an image before it is loaded:
 * its proportions (after the crop of the editor) and the widths for srcset.
 *
 * Pure calculation: no image processing, no database query.
 *
 * Usage: <f:variable name="info" value="{t3m:imageInfo(image: file, maxWidth: 900, columns: 4, layout: 'classic')}" />
 * Result: {width, height, small, large, sizes}
 */
final class ImageInfoViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('image', 'object', 'File or file reference', true);
        $this->registerArgument('cropVariant', 'string', 'Crop variant', false, 'default');
        $this->registerArgument('maxWidth', 'int', 'Largest delivered width in pixels, 0 = original file', false, 900);
        $this->registerArgument('columns', 'int', 'Number of columns on large screens', false, 4);
        $this->registerArgument('layout', 'string', 'Gallery layout; "justified": image widths do not follow the columns', false, '');
    }

    /**
     * @return array{width: int, height: int, small: int, large: int, sizes: string}
     */
    public function render(): array
    {
        [$width, $height] = $this->dimensions($this->arguments['image'], (string)$this->arguments['cropVariant']);

        $large = max(0, (int)$this->arguments['maxWidth']);
        if ($large > 0 && $width > 0) {
            $large = min($large, $width);
        }
        $small = $large > 0 ? (int)round($large / 2) : 0;

        $columns = max(1, min(6, (int)$this->arguments['columns']));
        $sizes = (string)$this->arguments['layout'] === 'justified'
            ? '(max-width: 575px) 70vw, 40vw'
            : '(max-width: 575px) 50vw, (max-width: 991px) ' . (int)ceil(100 / min(3, $columns)) . 'vw, ' . (int)ceil(100 / $columns) . 'vw';

        return [
            'width' => $width,
            'height' => $height,
            'small' => $small,
            'large' => $large,
            'sizes' => $sizes,
        ];
    }

    /**
     * Width and height of the visible part of the image (crop area of the variant, else the whole file).
     * Falls back to 4:3 when the file has no dimensions (e.g. some SVG files).
     *
     * @return array{0: int, 1: int}
     */
    private function dimensions(mixed $image, string $cropVariant): array
    {
        if (!$image instanceof FileInterface) {
            return [4, 3];
        }
        $width = (int)($image->hasProperty('width') ? $image->getProperty('width') : 0);
        $height = (int)($image->hasProperty('height') ? $image->getProperty('height') : 0);

        $crop = $image->hasProperty('crop') ? (string)$image->getProperty('crop') : '';
        if ($crop !== '' && $width > 0 && $height > 0) {
            try {
                $area = CropVariantCollection::create($crop)->getCropArea($cropVariant !== '' ? $cropVariant : 'default');
                if (!$area->isEmpty()) {
                    $absolute = $area->makeAbsoluteBasedOnFile($image);
                    $width = (int)round($absolute->getWidth());
                    $height = (int)round($absolute->getHeight());
                }
            } catch (\Throwable) {
                // Invalid crop configuration: keep the dimensions of the file
            }
        }

        if ($width < 1 || $height < 1) {
            return [4, 3];
        }
        return [$width, $height];
    }
}
