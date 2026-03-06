@php
    $newsletter_section = get_field('newsletter_section', 'option');
@endphp

@if ($newsletter_section)
    @php
        $title = $newsletter_section['newsletter_title'] ?? '';
        $title_mobile = $newsletter_section['newsletter_title_mobile'] ?? '';
        $description = $newsletter_section['newsletter_description'] ?? '';
        $name_label = $newsletter_section['name_field_title'] ?? 'Name';
        $email_label = $newsletter_section['email_address_field_title'] ?? 'Email Address';
        $button_title = $newsletter_section['newsletter_button_title'] ?? 'Subscribe';
        $button_link = $newsletter_section['newsletter_button_link'] ?? '';

        $button_url = is_array($button_link) ? ($button_link['url'] ?? '#') : ($button_link ?: '#');
        $button_target = is_array($button_link) ? ($button_link['target'] ?? '_self') : '_self';

        $newsletter_image = $newsletter_section['newsletter_image'] ?? null;
    @endphp

    <div class="newsletter-modal" id="newsletter-modal" role="dialog" aria-modal="true" aria-labelledby="newsletter-modal-title" aria-hidden="true">
        <div class="newsletter-modal__overlay" data-newsletter-close></div>
        <div class="newsletter-modal__container{{ $newsletter_image ? ' has-image' : '' }}">
            <button type="button" class="newsletter-modal__close" data-newsletter-close aria-label="Close newsletter popup">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="newsletter-modal__content">
                {{-- @if ($title)
                    <h2 class="newsletter-modal__title newsletter-modal__title--desktop" id="newsletter-modal-title">
                        {{ esc_html($title) }}
                    </h2>
                @endif

                @if ($title_mobile)
                    <h2 class="newsletter-modal__title newsletter-modal__title--mobile" id="newsletter-modal-title-mobile">
                        {{ esc_html($title_mobile) }}
                    </h2>
                @elseif ($title)
                    <h2 class="newsletter-modal__title newsletter-modal__title--mobile" id="newsletter-modal-title-mobile">
                        {{ esc_html($title) }}
                    </h2>
                @endif --}}

                @if ($description)
                    <p class="newsletter-modal__description">
                        {{ esc_html($description) }}
                    </p>
                @endif

                <form class="newsletter-modal__form" id="newsletter-form">
                    <div class="newsletter-modal__field">
                        <label for="newsletter-name" class="newsletter-modal__label">
                            {{ esc_html($name_label) }}
                        </label>
                        <input
                            type="text"
                            id="newsletter-name"
                            name="name"
                            class="newsletter-modal__input"
                            required
                            autocomplete="name"
                        >
                    </div>

                    <div class="newsletter-modal__field">
                        <label for="newsletter-email" class="newsletter-modal__label">
                            {{ esc_html($email_label) }}
                        </label>
                        <input
                            type="email"
                            id="newsletter-email"
                            name="email"
                            class="newsletter-modal__input"
                            required
                            autocomplete="email"
                        >
                    </div>

                    <div class="newsletter-modal__actions">
                        @if ($button_url && $button_url !== '#')
                            <a
                                href="{{ esc_url($button_url) }}"
                                target="{{ esc_attr($button_target) }}"
                                class="btn newsletter-modal__btn"
                                data-event="Newsletter Subscribe"
                            >
                                {{ esc_html($button_title) }}
                            </a>
                        @else
                            <button type="submit" class="btn newslettermodal-btn" data-event="Newsletter Subscribe">
                                {{ esc_html($button_title) }}
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            @if ($newsletter_image)
                <div class="newsletter-modal__image">
                    <img src="{{ esc_url($newsletter_image['url']) }}" alt="{{ esc_attr($newsletter_image['alt'] ?? '') }}">
                </div>
            @endif
        </div>
    </div>
@endif
