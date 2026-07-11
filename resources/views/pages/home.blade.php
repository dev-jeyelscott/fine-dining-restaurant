<x-layouts.public
    :title="$page?->meta_title ?: ($settings['meta_title'] ?? 'Home')"
    :description="$page?->meta_description ?: ($settings['meta_description'] ?? 'Fine dining restaurant for families, guests, events, reservation requests, and order inquiries.')"
>
    @php
        $restaurantName = $settings['restaurant_name'] ?? config('app.name');
        $phone = $settings['phone'] ?? null;
        $phoneDigits = is_string($phone) ? preg_replace('/\D+/', '', $phone) : null;
        $phoneTelTarget = is_string($phone)
            && is_string($phoneDigits)
            && $phoneDigits !== ''
                ? (str_starts_with(ltrim($phone), '+') ? '+' : '').$phoneDigits
                : null;
    @endphp

    <div data-home-motion>
    <x-public.homepage-hero
        eyebrow="Fine dining, thoughtfully served"
        title="{{ $page?->title ?: 'An elevated dining experience for every occasion' }}"
        description="{{ $page?->excerpt ?: 'Discover carefully prepared dishes, warm hospitality, and an elegant atmosphere for family dinners, intimate celebrations, and memorable events.' }}"
        :image="$heroImage"
        :image-alt="$heroImage?->alt_text ?: $heroImage?->title ?: 'Elegant restaurant dining room'"
        primary-label="Request a Reservation"
        :primary-url="route('reservation-request.create')"
        secondary-label="View Our Menu"
        :secondary-url="route('menu')"
    />

    <section id="restaurant-story" data-gsap="section" class="overflow-hidden bg-brand-ivory py-24 text-brand-ink lg:py-32">
        <div class="mx-auto grid max-w-7xl items-center gap-16 px-5 sm:px-6 lg:grid-cols-2 lg:px-10">
            <div class="relative mx-auto w-full max-w-xl lg:mx-0">
                <div data-gsap="frame" class="absolute -bottom-7 -right-7 hidden h-2/3 w-2/3 border border-brand-gold/70 lg:block" aria-hidden="true"></div>

                <div data-gsap="image" class="relative aspect-[4/5] overflow-hidden bg-brand-paper shadow-[0_30px_80px_rgba(23,25,22,0.18)]">
                    @if ($storyImage?->image_url)
                        <x-public.responsive-image
                            :image="$storyImage"
                            :alt="$storyImage->alt_text ?: $storyImage->title ?: 'Chef-prepared fine-dining dish'"
                            variant="large"
                            sizes="(min-width: 1024px) 45vw, 100vw"
                            width="960"
                            height="1200"
                            img-class="h-full w-full object-cover"
                        />
                    @else
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_25%,rgba(201,164,93,0.28),transparent_32%),linear-gradient(145deg,#d8cfbd,#8b7960)]"></div>
                        <p class="absolute inset-x-8 bottom-8 border-t border-white/45 pt-4 text-xs font-semibold uppercase tracking-[0.24em] text-white/80">
                            Restaurant imagery can be managed from the admin panel
                        </p>
                    @endif
                </div>
            </div>

            <div>
                <x-public.section-heading
                    eyebrow="Our Story"
                    title="Fine dining made warm and welcoming"
                    :description="$page?->content ?: 'Our restaurant brings thoughtful preparation, gracious service, and a refined atmosphere together for meals that feel both special and comfortable.'"
                    align="left"
                    theme="light"
                />

                <p class="mt-8 max-w-xl text-base leading-8 text-brand-muted">
                    Whether you are planning a family dinner, an intimate celebration, or an important gathering, our team is here to create a memorable experience from the first welcome to the final course.
                </p>

                <a
                    href="{{ route('gallery') }}"
                    class="group mt-9 inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:text-brand-gold-dark"
                >
                    Explore the experience
                    <span class="transition duration-300 group-hover:translate-x-2" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <section data-gsap="menu" class="bg-brand-ink py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-10">
            <x-public.section-heading
                eyebrow="The Menu"
                title="Signature selections"
                description="A curated preview of visible menu items managed by the restaurant team."
            />

            <div class="mt-14 grid gap-10 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @forelse ($featuredMenuItems as $item)
                    <x-public.menu-card :item="$item" variant="luxury" />
                @empty
                    <x-public.alert type="warning" class="md:col-span-2 lg:col-span-3">
                        No visible menu items yet. Add menu items from the Filament admin panel.
                    </x-public.alert>
                @endforelse
            </div>

            <div class="mt-14 text-center">
                <a
                    href="{{ route('menu') }}"
                    class="inline-flex min-h-12 items-center justify-center border border-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold transition duration-300 hover:bg-brand-gold hover:text-brand-ink"
                >
                    View Full Menu
                </a>
            </div>
        </div>
    </section>

    <section class="grid lg:grid-cols-2">
        <article data-gsap="panel" class="relative isolate flex min-h-[32rem] items-center overflow-hidden bg-brand-ink px-5 py-20 sm:px-10 lg:px-16">
            @if ($heroImage?->image_url)
                <x-public.responsive-image
                    :image="$heroImage"
                    alt=""
                    variant="large"
                    sizes="(min-width: 1024px) 50vw, 100vw"
                    width="1200"
                    height="900"
                    img-class="absolute inset-0 -z-20 h-full w-full object-cover"
                />
            @endif
            <div class="absolute inset-0 -z-10 bg-brand-ink/82"></div>

            <div class="mx-auto max-w-lg text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-gold">Dining With Us</p>
                <h2 class="mt-5 font-display text-4xl leading-tight text-white sm:text-5xl">Plan your dining experience</h2>
                <p class="mt-6 text-base leading-8 text-stone-300">
                    Share your preferred date, time, and party size. Our team will review your request and contact you to confirm availability.
                </p>
                <a
                    href="{{ route('reservation-request.create') }}"
                    class="mt-9 inline-flex min-h-12 items-center justify-center bg-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:bg-brand-gold-dark hover:text-white"
                >
                    Request a Reservation
                </a>
            </div>
        </article>

        <article data-gsap="panel" class="relative isolate flex min-h-[32rem] items-center overflow-hidden bg-brand-paper px-5 py-20 text-brand-ink sm:px-10 lg:px-16">
            @if ($storyImage?->image_url)
                <x-public.responsive-image
                    :image="$storyImage"
                    alt=""
                    variant="large"
                    sizes="(min-width: 1024px) 50vw, 100vw"
                    width="1200"
                    height="900"
                    img-class="absolute inset-0 -z-20 h-full w-full object-cover opacity-15 grayscale"
                />
            @endif
            <div class="absolute inset-0 -z-10 bg-brand-paper/88"></div>

            <div class="mx-auto max-w-lg text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-gold-dark">Dining Beyond Our Tables</p>
                <h2 class="mt-5 font-display text-4xl leading-tight sm:text-5xl">Bring the experience home</h2>
                <p class="mt-6 text-base leading-8 text-brand-muted">
                    Tell us what you would like for pickup or delivery. Our team will review availability, confirm the final total, and arrange the details directly with you.
                </p>
                <a
                    href="{{ route('order-inquiry.create') }}"
                    class="mt-9 inline-flex min-h-12 items-center justify-center bg-brand-ink px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ivory transition hover:bg-brand-ink-soft"
                >
                    Submit Order Inquiry
                </a>
            </div>
        </article>
    </section>

    <section data-gsap="section" class="overflow-hidden bg-white py-24 text-brand-ink lg:py-32">
        <div class="mx-auto grid max-w-7xl items-center gap-16 px-5 sm:px-6 lg:grid-cols-2 lg:px-10">
            <div>
                <x-public.section-heading
                    eyebrow="Banquet Hall"
                    title="A refined setting for meaningful occasions"
                    description="Host family gatherings, private dining, celebrations, and corporate events in an elegant space supported by attentive restaurant service."
                    align="left"
                    theme="light"
                />

                <p class="mt-8 max-w-xl text-base leading-8 text-brand-muted">
                    Share your event requirements with our team to discuss the space, menu options, guest needs, and availability. All banquet arrangements are reviewed and confirmed directly by the restaurant.
                </p>

                <a
                    href="{{ route('banquet-hall') }}"
                    class="group mt-9 inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink transition hover:text-brand-gold-dark"
                >
                    Explore Banquet Hall
                    <span class="transition duration-300 group-hover:translate-x-2" aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="relative">
                <div data-gsap="frame" class="absolute -left-7 -top-7 hidden h-full w-full border border-brand-gold/70 lg:block" aria-hidden="true"></div>
                <div data-gsap="image" class="relative aspect-[4/3] overflow-hidden bg-brand-paper shadow-[0_30px_80px_rgba(23,25,22,0.16)]">
                    @if ($banquetImage?->image_url)
                        <x-public.responsive-image
                            :image="$banquetImage"
                            :alt="$banquetImage->alt_text ?: $banquetImage->title ?: 'Elegant banquet hall setting'"
                            variant="large"
                            sizes="(min-width: 1024px) 45vw, 100vw"
                            width="1200"
                            height="900"
                            img-class="h-full w-full object-cover"
                        />
                    @else
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_65%_30%,rgba(201,164,93,0.32),transparent_35%),linear-gradient(145deg,#4d4437,#171916)]"></div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section data-gsap="gallery" class="bg-brand-ink py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-10">
            <x-public.section-heading
                eyebrow="Gallery"
                title="Ambiance, dishes, and celebrations"
                description="A visual preview of the dining experience, managed through the restaurant admin panel."
            />

            @if ($galleryImages->isNotEmpty())
                <div class="mt-14 grid auto-rows-[15rem] gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($galleryImages->take(5) as $image)
                        <article data-gsap="tile" @class([
                            'group relative overflow-hidden bg-brand-ink-soft',
                            'md:row-span-2 lg:col-span-2' => $loop->first,
                        ])>
                            @if ($image->image_url)
                                <x-public.responsive-image
                                    :image="$image"
                                    :alt="$image->alt_text ?: $image->title ?: 'Restaurant gallery image'"
                                    :variant="$loop->first ? 'large' : 'card'"
                                    :sizes="$loop->first ? '(min-width: 1024px) 66vw, 100vw' : '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw'"
                                    width="1200"
                                    height="900"
                                    img-class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                />
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-brand-ink/80 via-transparent to-transparent opacity-75 transition group-hover:opacity-95"></div>

                            @if ($image->title || $image->category)
                                <div class="absolute inset-x-0 bottom-0 p-6">
                                    @if ($image->category)
                                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.24em] text-brand-gold">
                                            {{ $image->category }}
                                        </p>
                                    @endif
                                    @if ($image->title)
                                        <h3 class="mt-2 font-display text-2xl text-white">{{ $image->title }}</h3>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <x-public.alert type="warning" class="mt-14">
                    No visible gallery images yet. Add images from the Filament admin panel.
                </x-public.alert>
            @endif

            <div class="mt-12 text-center">
                <a
                    href="{{ route('gallery') }}"
                    class="inline-flex min-h-12 items-center justify-center border border-brand-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-gold transition hover:bg-brand-gold hover:text-brand-ink"
                >
                    View the Gallery
                </a>
            </div>
        </div>
    </section>

    <section data-gsap="section" class="bg-brand-paper py-24 text-brand-ink lg:py-28">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-10">
            <div>
                <p data-gsap-reveal class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-gold-dark">Plan Your Visit</p>
                <h2 data-gsap-reveal class="mt-5 max-w-2xl font-display text-4xl leading-tight sm:text-5xl">
                    We look forward to welcoming you to {{ $restaurantName }}.
                </h2>
                <p data-gsap-reveal class="mt-6 max-w-2xl text-base leading-8 text-brand-muted">
                    Explore the menu, send a Reservation Request, or contact our team for directions and general dining questions.
                </p>
            </div>

            <dl class="grid content-start gap-7 border-l-0 border-brand-gold/40 lg:border-l lg:pl-12">
                @if ($settings['address'] ?? null)
                    <div data-gsap-reveal>
                        <dt class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-gold-dark">Address</dt>
                        <dd class="mt-2 text-base leading-7 text-brand-muted">{{ $settings['address'] }}</dd>
                    </div>
                @endif

                @if ($phoneTelTarget !== null)
                    <div data-gsap-reveal>
                        <dt class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-gold-dark">Phone</dt>
                        <dd class="mt-2">
                            <a href="tel:{{ $phoneTelTarget }}" class="text-base text-brand-muted transition hover:text-brand-ink">
                                {{ $phone }}
                            </a>
                        </dd>
                    </div>
                @endif

                @if ($settings['email'] ?? null)
                    <div data-gsap-reveal>
                        <dt class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-gold-dark">Email</dt>
                        <dd class="mt-2">
                            <a href="mailto:{{ $settings['email'] }}" class="text-base text-brand-muted transition hover:text-brand-ink">
                                {{ $settings['email'] }}
                            </a>
                        </dd>
                    </div>
                @endif

                <div data-gsap-reveal class="flex flex-wrap gap-3 pt-2">
                    @if ($settings['map_link'] ?? null)
                        <a
                            href="{{ $settings['map_link'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex min-h-12 items-center justify-center border border-brand-ink px-6 py-3 text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink transition hover:bg-brand-ink hover:text-brand-ivory"
                        >
                            Open Location Map
                        </a>
                    @endif

                    <a
                        href="{{ route('contact.create') }}"
                        class="inline-flex min-h-12 items-center justify-center bg-brand-gold px-6 py-3 text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink transition hover:bg-brand-gold-dark hover:text-white"
                    >
                        Contact Us
                    </a>
                </div>
            </dl>
        </div>
    </section>
    </div>
</x-layouts.public>
