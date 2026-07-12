@php
    $restaurantName = (string) config('app.name');
@endphp

<span class="admin-brand-lockup">
    <span class="admin-brand-lockup-name">
        {{ $restaurantName }}
    </span>

    <span
        class="admin-brand-lockup-mark"
        aria-hidden="true"
    ></span>
</span>
