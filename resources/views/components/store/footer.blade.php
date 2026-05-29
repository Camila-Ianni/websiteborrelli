<footer class="bg-primary dark:bg-pure-black pt-section-gap pb-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter px-margin-desktop py-section-gap max-w-container-max mx-auto">
        <div class="space-y-6">
            <div class="font-headline-md text-headline-md font-black text-pure-white">{{ __('messages.brand_name') }}</div>
            <p class="font-body-md text-body-md text-on-primary-container">{{ __('messages.footer_tagline') }}</p>
            <div class="flex gap-4">
                <span class="material-symbols-outlined text-energy-lime cursor-pointer">public</span>
                <span class="material-symbols-outlined text-energy-lime cursor-pointer">share</span>
                <span class="material-symbols-outlined text-energy-lime cursor-pointer">chat_bubble</span>
            </div>
        </div>
        <div class="space-y-6">
            <h5 class="font-label-bold text-label-bold text-pure-white uppercase tracking-widest">{{ __('messages.footer_shop') }}</h5>
            <ul class="space-y-4">
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="{{ route('catalog.index', ['categories' => 'proteina']) }}">{{ __('messages.nav_protein') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="{{ route('catalog.index', ['categories' => 'creatina']) }}">{{ __('messages.nav_creatine') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="{{ route('catalog.index', ['categories' => 'aminoacidos']) }}">{{ __('messages.nav_amino') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="{{ route('catalog.index') }}">{{ __('messages.footer_bundles') }}</a></li>
            </ul>
        </div>
        <div class="space-y-6">
            <h5 class="font-label-bold text-label-bold text-pure-white uppercase tracking-widest">{{ __('messages.footer_support') }}</h5>
            <ul class="space-y-4">
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_shipping') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_returns') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_privacy') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_terms') }}</a></li>
            </ul>
        </div>
        <div class="space-y-6">
            <h5 class="font-label-bold text-label-bold text-pure-white uppercase tracking-widest">{{ __('messages.footer_social') }}</h5>
            <ul class="space-y-4">
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_instagram') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_whatsapp') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_twitter') }}</a></li>
                <li><a class="font-body-md text-on-primary-container hover:text-pure-white transition-colors" href="#">{{ __('messages.footer_youtube') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="max-w-container-max mx-auto px-margin-desktop pt-12 border-t border-white/10 text-center">
        <p class="font-label-sm text-label-sm text-on-primary-container">{{ __('messages.footer_copyright') }}</p>
    </div>
</footer>
