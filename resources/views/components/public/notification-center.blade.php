<div
    x-data="{
        notification: null,
        open(detail) {
            this.notification = detail;
            this.$nextTick(() => this.$refs.notification?.focus());
        },
        close() {
            this.notification = null;
        },
    }"
    @inquiry-notification.window="open($event.detail)"
    @keydown.escape.window="close()"
    class="pointer-events-none fixed inset-x-4 top-24 z-[60] flex justify-center sm:inset-x-auto sm:right-6 sm:w-full sm:max-w-md"
>
    <div
        x-show="notification"
        x-cloak
        x-transition
        x-ref="notification"
        tabindex="-1"
        class="pointer-events-auto w-full border p-5 text-sm leading-7 shadow-[0_20px_60px_rgba(23,25,22,0.2)]"
        :class="notification?.type === 'success' ? 'border-emerald-700/25 bg-emerald-50 text-emerald-900' : 'border-brand-burgundy/25 bg-red-50 text-brand-burgundy'"
        :role="notification?.type === 'success' ? 'status' : 'alert'"
        :aria-live="notification?.type === 'success' ? 'polite' : 'assertive'"
        aria-atomic="true"
    >
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="font-semibold" x-text="notification?.title"></p>
                <p class="mt-1" x-text="notification?.message"></p>
            </div>
            <button
                type="button"
                class="shrink-0 text-lg leading-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                aria-label="Dismiss notification"
                @click="close()"
            >&times;</button>
        </div>
    </div>
</div>
