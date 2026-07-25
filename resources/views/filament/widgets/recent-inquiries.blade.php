<x-filament-widgets::widget>
    <section
        class="admin-dashboard-panel"
        aria-labelledby="recent-inquiries-heading"
    >
        <header class="admin-dashboard-panel__header">
            <div>
                <p class="admin-dashboard-panel__eyebrow">
                    Guest requests
                </p>

                <h2
                    id="recent-inquiries-heading"
                    class="admin-dashboard-panel__title"
                >
                    Recent inquiries
                </h2>

                <p class="admin-dashboard-panel__description">
                    Review the latest customer requests and follow up manually.
                </p>
            </div>
        </header>

        <div
            wire:loading.flex
            class="admin-dashboard-state"
            role="status"
            aria-live="polite"
        >
            <x-filament::icon
                icon="heroicon-o-arrow-path"
                class="admin-dashboard-state__icon admin-dashboard-state__icon--spin"
            />

            <div>
                <strong>Loading inquiries</strong>
                <p>Preparing the latest customer requests.</p>
            </div>
        </div>

        <div wire:loading.remove>
            @if ($loadError)
                <div
                    class="admin-dashboard-state admin-dashboard-state--error"
                    role="status"
                >
                    <x-filament::icon
                        icon="heroicon-o-exclamation-triangle"
                        class="admin-dashboard-state__icon"
                    />

                    <div>
                        <strong>Inquiries are temporarily unavailable</strong>

                        <p>
                            The dashboard could not load recent inquiries.
                            The error has been recorded for investigation.
                        </p>
                    </div>
                </div>
            @else
                <div class="admin-dashboard-inquiries">
                    @forelse ($inquiries as $inquiry)
                        <a
                            href="{{ $inquiry['url'] }}"
                            class="admin-dashboard-inquiry {{ $inquiry['is_read'] ? '' : 'is-unread' }}"
                            aria-label="View {{ $inquiry['type'] }} from {{ $inquiry['customer_name'] }}"
                        >
                            <div class="admin-dashboard-inquiry__content">
                                <div class="admin-dashboard-inquiry__heading">
                                    <span class="admin-dashboard-inquiry__type">
                                        {{ $inquiry['type'] }}
                                    </span>

                                    @unless ($inquiry['is_read'])
                                        <span class="admin-dashboard-badge">
                                            New
                                        </span>
                                    @endunless
                                </div>

                                <strong class="admin-dashboard-inquiry__name">
                                    {{ $inquiry['customer_name'] }}
                                </strong>

                                <span class="admin-dashboard-inquiry__summary">
                                    {{ $inquiry['summary'] }}
                                </span>
                            </div>

                            <div class="admin-dashboard-inquiry__meta">
                                <time
                                    datetime="{{ $inquiry['created_at']->toIso8601String() }}"
                                >
                                    {{ $inquiry['created_at']->diffForHumans() }}
                                </time>

                                <x-filament::icon
                                    icon="heroicon-m-chevron-right"
                                    class="admin-dashboard-inquiry__arrow"
                                />
                            </div>
                        </a>
                    @empty
                        <div class="admin-dashboard-state">
                            <x-filament::icon
                                icon="heroicon-o-inbox"
                                class="admin-dashboard-state__icon"
                            />

                            <div>
                                <strong>No inquiries yet</strong>

                                <p>
                                    New Reservation Requests, Order Inquiries,
                                    and Contact Inquiries will appear here.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </section>
</x-filament-widgets::widget>
