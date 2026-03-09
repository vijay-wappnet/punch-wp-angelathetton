@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif

<section id="{{ $blockId }}" class="faqs-accordion-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12">
                @if($faqs && count($faqs) > 0)
                    <div class="faqs-accordion">
                        @foreach($faqs as $index => $faq)
                            @php
                                $question = $faq['question'] ?? '';
                                $answer = $faq['answer'] ?? '';
                            @endphp
                            <div class="faq-item" data-faq-index="{{ $index }}">
                                <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-answer-{{ $blockId }}-{{ $index }}">
                                    <span class="faq-question-text">
                                        {{ esc_html($question) }}
                                    </span>
                                    <span class="faq-icon">
                                        <img src="{{ Vite::asset('resources/images/bottom_arrow.svg') }}" alt="{{ esc_attr__('Toggle answer', 'sage') }}" class="icon">
                                    </span>
                                </button>
                                <div class="faq-answer" id="faq-answer-{{ $blockId }}-{{ $index }}" aria-hidden="true">
                                    <div class="faq-answer-content">
                                        {!! wp_kses_post(nl2br(esc_html($answer))) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    @if($isPreview)
                        <p class="no-faqs-message">{{ __('No FAQs added yet. Please add questions and answers in the block settings.', 'sage') }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>
