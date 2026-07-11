export default function inquiryForm() {
    return {
        submitting: false,
        popup: null,
        errors: {},
        touched: new Set(),
        fulfillmentType: 'pickup',

        init() {
            this.fulfillmentType = this.$el.dataset.initialFulfillment || 'pickup';
            this.$el.querySelectorAll('input, textarea, select').forEach((field) => {
                field.addEventListener('blur', () => this.touch(field));
                field.addEventListener('input', () => this.revalidate(field));
                field.addEventListener('change', () => this.revalidate(field));
            });
        },

        touch(field) {
            this.touched.add(field.name);
            this.validateField(field);
        },

        revalidate(field) {
            if (this.touched.has(field.name)) {
                this.validateField(field);
            }
        },

        validateField(field) {
            if (field.disabled || field.name === 'website') {
                return true;
            }

            const value = field.value.trim();
            let message = '';

            if (field.required && value === '') {
                message = 'This field is required.';
            } else if (field.type === 'email' && value !== '' && !field.validity.valid) {
                message = 'Enter a valid email address.';
            } else if (field.name === 'quantity' && value !== '' && (!Number.isInteger(Number(value)) || Number(value) < 1 || Number(value) > 999)) {
                message = 'Enter a quantity between 1 and 999.';
            }

            this.setFieldError(field, message);

            return message === '';
        },

        validateAll() {
            let valid = true;

            this.$el.querySelectorAll('input, textarea, select').forEach((field) => {
                if (field.name !== 'website') {
                    this.touched.add(field.name);
                    valid = this.validateField(field) && valid;
                }
            });

            return valid;
        },

        setFieldError(field, message) {
            const key = field.name;

            if (message) {
                this.errors[key] = [message];
            } else {
                delete this.errors[key];
            }

            field.setAttribute('aria-invalid', message ? 'true' : 'false');
            let error = this.$el.querySelector(`[data-inquiry-error-for="${CSS.escape(key)}"]`);

            if (!error && message) {
                error = document.createElement('p');
                error.dataset.inquiryErrorFor = key;
                error.className = 'mt-2 text-sm text-brand-burgundy';
                field.insertAdjacentElement('afterend', error);
            }

            if (error) {
                error.textContent = message;
                error.hidden = !message;
            }
        },

        applyServerErrors(errors) {
            this.errors = errors;

            Object.entries(errors).forEach(([key, messages]) => {
                const field = this.$el.querySelector(`[name="${CSS.escape(key)}"]`);

                if (field) {
                    this.touched.add(key);
                    this.setFieldError(field, messages[0]);
                }
            });
        },

        firstInvalidField() {
            return this.$el.querySelector('[aria-invalid="true"]');
        },

        showPopup(type, title, message) {
            this.$dispatch('inquiry-notification', { type, title, message });
        },

        async submit() {
            if (this.submitting || !this.validateAll()) {
                const firstInvalid = this.firstInvalidField();
                firstInvalid?.focus();
                this.showPopup('error', 'Please review your inquiry', 'Correct the highlighted fields before submitting.');
                return;
            }

            this.submitting = true;
            this.popup = null;

            try {
                const response = await fetch(this.$el.action, {
                    method: 'POST',
                    body: new FormData(this.$el),
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
                const payload = await response.json().catch(() => ({}));

                if (response.status === 422) {
                    this.applyServerErrors(payload.errors ?? {});
                    this.showPopup('error', 'Please review your inquiry', payload.message ?? 'Correct the highlighted fields and try again.');
                    this.firstInvalidField()?.focus();
                    return;
                }

                if (response.status === 419) {
                    this.showPopup('error', 'Session expired', 'Refresh the page and submit your inquiry again.');
                    return;
                }

                if (response.status === 429) {
                    this.showPopup('error', 'Please try again shortly', 'Too many requests were received.');
                    return;
                }

                if (!response.ok || payload.success !== true) {
                    throw new Error('Unexpected inquiry response');
                }

                this.$el.reset();
                this.$dispatch('inquiry-form:reset');
                this.showPopup('success', 'Order Inquiry received', payload.message);
            } catch (error) {
                this.showPopup('error', 'Unable to send inquiry', 'Please check your connection and try again.');
            } finally {
                this.submitting = false;
            }
        },
    };
}
