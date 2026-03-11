<?php

namespace App\Blocks;

class ArticleReadTimeAuthorDetailsSection
{
    /**
     * Render the Article Read Time Author Details Section block
     *
     * @param array $block
     * @param string $content
     * @param bool $is_preview
     * @param int $post_id
     * @return void
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $currentPostId = $post_id ?: get_the_ID();

        if (!$currentPostId) {
            return;
        }

        $hideThisBlockInMobile = (bool) get_field('hide_this_block_in_mobile');
        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');

        $blockId = 'artad-' . ($block['id'] ?? uniqid());
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        $backgroundStyle = '';
        if (!empty($sectionBg)) {
            $backgroundStyle = 'background-color: ' . esc_attr($sectionBg) . ';';
        }

        $readTimeMinutes = self::calculateReadTimeFromSupportedBlocks($currentPostId);

        $authorId = (int) get_post_field('post_author', $currentPostId);
        $authorName = $authorId ? get_the_author_meta('display_name', $authorId) : '';

        echo view('blocks.article-read-time-author-details-section', [
            'blockId'                => $blockId,
            'responsiveCss'          => $responsiveCss,
            'backgroundStyle'        => $backgroundStyle,
            'hideThisBlockInMobile'  => $hideThisBlockInMobile,
            'readTimeMinutes'        => $readTimeMinutes,
            'authorName'             => $authorName,
            'is_preview'             => $is_preview,
        ]);
    }

    /**
     * Calculate read time from supported ACF blocks only
     *
     * Supported:
     * - acf/introtext-section => content
     * - acf/faqs-accordion-section => faqs repeater question/answer
     *
     * @param int $postId
     * @return int
     */
    protected static function calculateReadTimeFromSupportedBlocks($postId)
    {
        $post = get_post($postId);

        if (!$post) {
            return 1;
        }

        $text = '';

        if (has_blocks($post->post_content)) {
            $blocks = parse_blocks($post->post_content);
            $text = self::extractTextFromBlocks($blocks);
        }

        $text = trim(wp_strip_all_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        $text = preg_replace('/\s+/u', ' ', $text);

        if (empty($text)) {
            return 1;
        }

        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = is_array($words) ? count($words) : 0;

        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Extract text recursively from supported blocks
     *
     * @param array $blocks
     * @return string
     */
    protected static function extractTextFromBlocks(array $blocks)
    {
        $textParts = [];

        foreach ($blocks as $parsedBlock) {
            $blockName = $parsedBlock['blockName'] ?? '';
            $data = $parsedBlock['attrs']['data'] ?? [];
            $data = is_array($data) ? $data : [];

            if ($blockName === 'acf/introtext-section' && !empty($data['content'])) {
                $textParts[] = wp_strip_all_tags($data['content']);
            }

            if ($blockName === 'acf/faqs-accordion-section') {
                $faqText = self::extractFaqsText($data);

                if (!empty($faqText)) {
                    $textParts[] = $faqText;
                }
            }

            if (!empty($parsedBlock['innerBlocks']) && is_array($parsedBlock['innerBlocks'])) {
                $textParts[] = self::extractTextFromBlocks($parsedBlock['innerBlocks']);
            }
        }

        return trim(implode(' ', array_filter($textParts)));
    }

    /**
     * Extract FAQ repeater text from ACF block data
     *
     * Handles:
     * - nested faqs array
     * - repeater row count with flattened keys
     * - flattened keys only
     *
     * @param array $data
     * @return string
     */
    protected static function extractFaqsText(array $data)
    {
        $parts = [];

        if (!empty($data['faqs']) && is_array($data['faqs'])) {
            foreach ($data['faqs'] as $faq) {
                if (!empty($faq['question'])) {
                    $parts[] = wp_strip_all_tags($faq['question']);
                }

                if (!empty($faq['answer'])) {
                    $parts[] = wp_strip_all_tags($faq['answer']);
                }
            }

            return trim(implode(' ', $parts));
        }

        if (isset($data['faqs']) && is_numeric($data['faqs'])) {
            $rows = (int) $data['faqs'];

            for ($i = 0; $i < $rows; $i++) {
                $questionKey = "faqs_{$i}_question";
                $answerKey = "faqs_{$i}_answer";

                if (!empty($data[$questionKey])) {
                    $parts[] = wp_strip_all_tags($data[$questionKey]);
                }

                if (!empty($data[$answerKey])) {
                    $parts[] = wp_strip_all_tags($data[$answerKey]);
                }
            }

            return trim(implode(' ', $parts));
        }

        foreach ($data as $key => $value) {
            if (preg_match('/^faqs_\d+_(question|answer)$/', (string) $key) && !empty($value)) {
                $parts[] = wp_strip_all_tags($value);
            }
        }

        return trim(implode(' ', $parts));
    }
}
