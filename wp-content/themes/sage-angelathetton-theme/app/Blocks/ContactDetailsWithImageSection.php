<?php

namespace App\Blocks;

class ContactDetailsWithImageSection
{
    /**
     * Render the Contact Details With Image Section block
     *
     * @param array $block The block settings and attributes
     * @param string $content The block content
     * @param bool $is_preview Whether we are in preview mode
     * @param int $post_id The post ID
     * @return void
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        // Address section fields
        $addressHeadingText = get_field('address_heading_text') ?? '';
        $addressHeadingLevel = get_field('address_heading_level') ?? 'h3';
        $addressContactDetails = get_field('address_contact_details');

        // Opening section fields
        $openingHeadingText = get_field('opening_heading_text') ?? '';
        $openingHeadingLevel = get_field('opening_heading_level') ?? 'h3';
        $openingContents = get_field('opening_contents') ?? '';

        // Image section fields
        $contactImage = get_field('contact_image');
        $showContactImageInMobile = get_field('show_contact_image_in_mobile') ?? true;

        // Section styling
        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'cdwis-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build background style
        $backgroundStyle = [];
        if (!empty($sectionBg)) {
            $backgroundStyle[] = 'background-color: ' . esc_attr($sectionBg);
        }
        $backgroundStyle = !empty($backgroundStyle) ? implode('; ', $backgroundStyle) . ';' : '';

        // Validate address contact details structure
        if (!is_array($addressContactDetails)) {
            $addressContactDetails = [];
        }

        // Render the Blade template with data
        echo view('blocks.contact-details-with-image-section', [
            'block'                   => $block,
            'blockId'                 => $blockId,
            'addressHeadingText'      => $addressHeadingText,
            'addressHeadingLevel'     => $addressHeadingLevel,
            'addressContactDetails'   => $addressContactDetails,
            'openingHeadingText'      => $openingHeadingText,
            'openingHeadingLevel'     => $openingHeadingLevel,
            'openingContents'         => $openingContents,
            'contactImage'            => $contactImage,
            'showContactImageInMobile'=> $showContactImageInMobile,
            'backgroundStyle'         => $backgroundStyle,
            'responsiveCss'           => $responsiveCss,
            'isPreview'               => $is_preview,
        ]);
    }
}
