<?php

namespace App\Blocks;

class ContainerWidthTwoColumnImageSection
{
    public static function render($block, $is_preview = false, $post_id = 0)
    {
        $images = get_field('images') ?: [];
        $hideFirstImageInMobile = (bool) get_field('hide_first_image_in_mobile');
        $hideSecondImageInMobile = (bool) get_field('hide_second_image_in_mobile');
        $section_bg = get_field('section_bg') ?: '#ffffff';
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'cwtcis-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Render the Blade template with data using view helper
        echo view('blocks.container-width-two-column-image-section', [
            'blockId'       => $blockId,
            'responsiveCss' => $responsiveCss,
            'images'        => $images,
            'section_bg'    => $section_bg,
            'is_preview'    => $is_preview,
            'hideFirstImageInMobile' => $hideFirstImageInMobile,
            'hideSecondImageInMobile' => $hideSecondImageInMobile,
        ]);
    }
}
