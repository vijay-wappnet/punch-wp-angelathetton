<?php

namespace App\Blocks;

// Register ACF field group
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_media_content_section',
        'title' => 'Media Content Section',
        'fields' => [
            [
                'key' => 'field_layout_toggle',
                'label' => 'Layout',
                'name' => 'show_left_side_image',
                'type' => 'true_false',
                'instructions' => 'Toggle between image on left/right',
                'default_value' => 1,
                'ui' => 1,
            ],
            [
                'key' => 'field_media_image',
                'label' => 'Media Image',
                'name' => 'media_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
            ],
            [
                'key' => 'field_background_image',
                'label' => 'Background Image',
                'name' => 'background_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
            ],
            [
                'key' => 'field_background_color',
                'label' => 'Background Color',
                'name' => 'background_color',
                'type' => 'color_picker',
                'enable_opacity' => 1,
                'return_format' => 'string',
            ],
            [
                'key' => 'field_title',
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'placeholder' => 'Enter section title',
            ],
            [
                'key' => 'field_heading_level',
                'label' => 'Heading Level',
                'name' => 'heading_level',
                'type' => 'radio',
                'choices' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'Paragraph',
                ],
                'default_value' => 'h2',
                'layout' => 'horizontal',
            ],
            [
                'key' => 'field_content',
                'label' => 'Content',
                'name' => 'content',
                'type' => 'wysiwyg',
                'toolbar' => 'full',
                'media_upload' => 0,
                'delay' => 0,
            ],
            [
                'key' => 'field_buttons_repeater',
                'label' => 'Buttons',
                'name' => 'intro_section_button',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Button',
                'sub_fields' => [
                    [
                        'key' => 'field_button_link',
                        'label' => 'Button Link',
                        'name' => 'button_link',
                        'type' => 'link',
                        'return_format' => 'array',
                    ],
                    [
                        'key' => 'field_button_aria_label',
                        'label' => 'Aria Label',
                        'name' => 'aria_label',
                        'type' => 'text',
                        'placeholder' => 'Enter aria label for accessibility',
                    ],
                    [
                        'key' => 'field_button_google_event',
                        'label' => 'Google Event Label',
                        'name' => 'button_google_event_label',
                        'type' => 'text',
                        'placeholder' => 'Enter Google Analytics event label',
                    ],
                    [
                        'key' => 'field_button_class',
                        'label' => 'Button Class',
                        'name' => 'button_class',
                        'type' => 'text',
                        'placeholder' => 'e.g., btn btn-primary',
                    ],
                ],
                'min' => 0,
                'max' => 5,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/media-content-section',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => [],
        'active' => true,
        'description' => '',
    ]);
});

class MediaContentSection
{
    /**
     * Render the Media Content Section block
     *
     * @param array $block The block settings and attributes
     * @param string $content The block content
     * @param bool $is_preview Whether we are in preview mode
     * @param int $post_id The post ID
     * @return void
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        // Get field values using ACF
        $showLeftImage = get_field('show_left_side_image') ?? true;
        $mediaImage = get_field('media_image');
        $title = get_field('title') ?? '';
        $headingLevel = get_field('heading_level');
        $contentText = get_field('content') ?? '';
        $buttons = get_field('intro_section_button');
        $backgroundImage = get_field('background_image');
        $backgroundColor = get_field('background_color');

        $backgroundStyle = [];

        // Always add background color if selected
        if (!empty($backgroundColor)) {
            $backgroundStyle[] = 'background-color: ' . esc_attr($backgroundColor);
        }

        // Always add background image if selected
        if (!empty($backgroundImage['url'])) {
            $backgroundStyle[] = 'background-image: url("' . esc_url($backgroundImage['url']) . '")';
            $backgroundStyle[] = 'background-size: cover';
            $backgroundStyle[] = 'background-position: center';
            $backgroundStyle[] = 'background-repeat: no-repeat';
        }

        // Convert array to inline style string
        $backgroundStyle = !empty($backgroundStyle) ? implode('; ', $backgroundStyle) . ';' : '';

        // Validate button structure
        if (!is_array($buttons)) {
            $buttons = [];
        }

        // Render the Blade template with data
        echo view('blocks.media-content-section', [
            'block'             => $block,
            'showLeftImage'     => $showLeftImage,
            'mediaImage'        => $mediaImage,
            'title'             => $title,
            'headingLevel'      => $headingLevel,
            'contentText'       => $contentText,
            'buttons'           => $buttons,
            'backgroundStyle'   => $backgroundStyle,
            'isPreview'         => $is_preview,
        ]);
    }
}
