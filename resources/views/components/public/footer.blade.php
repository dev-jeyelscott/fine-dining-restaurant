@php
    $restaurantName = \App\Models\SiteSetting::value('restaurant_name', config('app.name'));
    $phone = \App\Models\SiteSetting::value('phone');
    $email = \App\Models\SiteSetting::value('email');
    $address = \App\Models\SiteSetting::value('address');
@endphp

<footer class="border-t border-white/10 bg-stone-950">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
            <h2 class="text-lg font-semibold text-amber-200">{{ $restaurantName }}</h2>
            <p class="mt-3 text-sm leading-6 text-stone-400">
                A fine-dining experience for families, guests, celebrations, and memorable gatherings.
            </p>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-widest text-stone-300">Contact</h3>

            <div class="mt-3 space-y-2 text-sm text-stone-400">
                @if ($phone)
                    <p>{{ $phone }}</p>
                @endif

                @if ($email)
                    <p>{{ $email }}</p>
                @endif

                @if ($address)
                    <p>{{ $address }}</p>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-widest text-stone-300">Visit Us</h3>
            <div class="mt-3 flex flex-col gap-2 text-sm text-stone-400">
                <a href="{{ route('menu') }}" class="hover:text-amber-200">View Our Menu</a>
                <a href="{{ route('reservation-request.create') }}" class="hover:text-amber-200">Request a Reservation</a>
                <a href="{{ route('order-inquiry.create') }}" class="hover:text-amber-200">Submit Order Inquiry</a>
                <a href="{{ route('contact.create') }}" class="hover:text-amber-200">Contact Us</a>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10 px-4 py-4 text-center text-xs text-stone-500">
        &copy; {{ now()->year }} {{ $restaurantName }}. All rights reserved.
    </div>
</footer>
