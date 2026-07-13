<x-filament-widgets::widget>
    <section
        class="admin-dashboard-panel admin-dashboard-panel--actions"
        aria-labelledby="quick-actions-heading"
    >
        <header class="admin-dashboard-panel__header">
            <div>
                <p class="admin-dashboard-panel__eyebrow">
                    Content management
                </p>

                <h2
                    id="quick-actions-heading"
                    class="admin-dashboard-panel__title"
                >
                    Quick actions
                </h2>

                <p class="admin-dashboard-panel__description">
                    Go directly to common website-content tasks.
                </p>
            </div>
        </header>

        <nav
            class="admin-dashboard-actions"
            aria-label="Website content quick actions"
        >
            @foreach ($quickActions as $action)
                <a
                    href="{{ $action['url'] }}"
                    class="admin-dashboard-action"
                    aria-label="{{ $action['label'] }}: {{ $action['description'] }}"
                >
                    <span class="admin-dashboard-action__icon-wrapper">
                        <x-filament::icon
                            :icon="$action['icon']"
                            class="admin-dashboard-action__icon"
                        />
                    </span>

                    <span class="admin-dashboard-action__content">
                        <strong>{{ $action['label'] }}</strong>
                        <span>{{ $action['description'] }}</span>
                    </span>

                    <x-filament::icon
                        icon="heroicon-m-arrow-up-right"
                        class="admin-dashboard-action__arrow"
                    />
                </a>
            @endforeach
        </nav>
    </section>
</x-filament-widgets::widget>
