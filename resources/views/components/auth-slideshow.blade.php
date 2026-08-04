<div class="hidden lg:block max-lg:order-1 md:col-span-3 lg:h-screen w-full relative overflow-hidden select-none"
    style="background-color: var(--color-dark);"
    x-data="{
        slides: {{ json_encode(($authImages ?? collect())->values()) }},
        active: 0,
        timer: null,
        init() {
            if (this.slides.length > 1) {
                this.timer = setInterval(() => {
                    this.active = (this.active + 1) % this.slides.length;
                }, 4000);
            }
        }
    }">

    @if(isset($authImages) && count($authImages) > 0)
        {{-- Slides --}}
        <template x-for="(slide, index) in slides" :key="index">
            <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out"
                :class="active === index ? 'opacity-100 z-10 pointer-events-auto' : 'opacity-0 z-0 pointer-events-none'">
                
                {{-- Slide Image --}}
                <img :src="slide.url" :alt="slide.title" class="w-full h-full object-cover object-center" />
                
                {{-- Dark Gradient Overlay for readability --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-black/20"></div>

                {{-- Caption --}}
                <div class="absolute bottom-14 left-10 right-10 text-white z-20">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-white/20 backdrop-blur-md border border-white/30 mb-3"
                        x-text="slide.badge">
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mb-1" x-text="slide.title"></h3>
                    <p class="text-sm text-gray-200 line-clamp-2 max-w-xl" x-text="slide.subtitle"></p>
                </div>
            </div>
        </template>

        {{-- Dots Indicators --}}
        <template x-if="slides.length > 1">
            <div class="absolute bottom-6 left-10 z-30 flex items-center gap-2">
                <template x-for="(slide, index) in slides" :key="'dot-' + index">
                    <button type="button" @click="active = index"
                        class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                        :class="active === index ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/75'"
                        :aria-label="'Slide ' + (index + 1)">
                    </button>
                </template>
            </div>
        </template>
    @else
        {{-- Fallback if no images are present in DB --}}
        <div class="absolute inset-0 w-full h-full">
            <img src="{{ asset('assets/background-auth.webp') }}" class="w-full h-full object-cover" alt="Andri Sablon Auth Background" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute bottom-14 left-10 right-10 text-white z-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-white/20 backdrop-blur-md border border-white/30 mb-3">
                    Andri Sablon
                </div>
                <h3 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mb-1">AN Mastery</h3>
                <p class="text-sm text-gray-200">Sistem Manajemen Konveksi Sablon — Gedangsewu, Tulungagung.</p>
            </div>
        </div>
    @endif
</div>
