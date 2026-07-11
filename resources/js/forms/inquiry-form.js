const successTitles = {
    contact: 'Contact Inquiry received',
    'order-inquiry': 'Order Inquiry received',
    'reservation-request': 'Reservation Request received',
};

export default function inquiryForm() {
    return {
        submitting: false,
        errors: {},
        touched: new Set(),
        fulfillmentType: 'pickup',

        init() {
            this.fulfillmentType = this.$el.dataset.initialFulfillment || this.$el.dataset.defaultFulfillment || 'pickup';

            this.$el.querySelectorAll('input, textarea, select').forEach((field) => {
                if (field.dataset.inquiryValidationBound === 'true') {
                    return;
                }

                field.dataset.inquiryValidationBound = 'true';
                field.addEventListener('blur', () => this.touch(field));
                field.addEventListener('input', () => this.revalidate(field));
                field.addEventListener('change', () => this.revalidate(field));
            });

            this.$watch('fulfillmentType', (value) => {
                if (value === 'delivery') {
                    return;
                }

                const deliveryAddress = this.$el.querySelector('[name="delivery_address"]');

                if (deliveryAddress) {
                    this.touched.delete(deliveryAddress.name);
                    this.clearFieldError(deliveryAddress);
                }
            });
        },

        touch(field) {
            if (!field.name || field.name === 'website') {
                return;
            }

            this.touched.add(field.name);
            this.validateField(field);
        },

        revalidate(field) {
            if (field.name && this.touched.has(field.name) && this.errors[field.name]) {
                this.validateField(field);
            }
        },

        validateField(field) {
            if (!field.name || field.name === 'website' || field.disabled) {
                this.clearFieldError(field);

                return true;
            }

            const value = field.value.trim();
            let message = '';

            if (field.required && value === '') {
                message = 'This field is required.';
            } else if (field.validity.typeMismatch) {
                message = field.type === 'email' ? 'Enter a valid email address.' : 'Enter a valid value.';
            } else if (field.validity.rangeUnderflow || field.validity.rangeOverflow || field.validity.stepMismatch || field.validity.badInput) {
                message = this.rangeMessage(field);
            } else if (field.validity.tooLong) {
                message = `Use no more than ${field.maxLength} characters.`;
            }

            this.setFieldError(field, message);

            return message === '';
        },

        rangeMessage(field) {
            if (field.type === 'date') {
                return 'Choose a valid available date.';
            }

            if (field.type === 'number') {
                const minimum = field.min || 'the allowed minimum';
                const maximum = field.max || 'the allowed maximum';

                return `Enter a whole number between ${minimum} and ${maximum}.`;
            }

            return 'Enter a valid value.';
        },

        validateAll() {
            let valid = true;

            this.$el.querySelectorAll('input, textarea, select').forEach((field) => {
                if (!field.name || field.name === 'website' || field.disabled) {
                    return;
                }

                this.touched.add(field.name);
                valid = this.validateField(field) && valid;
            });

            return valid;
        },

        fieldErrorId(field) {
            const key = field.name.replace(/[^a-zA-Z0-9_-]/g, '-');
            const prefix = this.$el.id || 'public-inquiry';

            return `${prefix}-${key}-error`;
        },

        errorInsertionTarget(field) {
            const matchingFields = this.$el.querySelectorAll(`[name="${CSS.escape(field.name)}"]`);
            const fieldset = field.closest('fieldset');

            return fieldset && matchingFields.length > 1 ? fieldset : field;
        },

        setFieldError(field, message) {
            if (!field.name) {
                return;
            }

            const key = field.name;
            const errorId = this.fieldErrorId(field);
            let error = this.$el.querySelector(`[data-inquiry-error-for="${CSS.escape(key)}"]`);

            if (!message) {
                this.clearFieldError(field);

                return;
            }

            this.errors[key] = [message];
            this.$el.querySelectorAll(`[name="${CSS.escape(key)}"]`).forEach((matchingField) => {
                matchingField.setAttribute('aria-invalid', 'true');
                const describedBy = new Set((matchingField.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean));
                describedBy.add(errorId);
                matchingField.setAttribute('aria-describedby', [...describedBy].join(' '));
            });

            if (!error) {
                error = document.createElement('p');
                error.id = errorId;
                error.dataset.inquiryErrorFor = key;
                error.dataset.inquiryGeneratedError = 'true';
                error.className = 'mt-2 text-sm text-brand-burgundy';
                this.errorInsertionTarget(field).insertAdjacentElement('afterend', error);
            }

            error.textContent = message;
            error.hidden = false;
        },

        clearFieldError(field) {
            if (!field?.name) {
                return;
            }

            const key = field.name;
            const errorId = this.fieldErrorId(field);
            delete this.errors[key];

            this.$el.querySelectorAll(`[name="${CSS.escape(key)}"]`).forEach((matchingField) => {
                matchingField.removeAttribute('aria-invalid');
                const describedBy = (matchingField.getAttribute('aria-describedby') || '')
                    .split(/\s+/)
                    .filter((value) => value && value !== errorId);

                if (describedBy.length > 0) {
                    matchingField.setAttribute('aria-describedby', describedBy.join(' '));
                } else {
                    matchingField.removeAttribute('aria-describedby');
                }
            });

            const error = this.$el.querySelector(`[data-inquiry-error-for="${CSS.escape(key)}"]`);

            if (error?.dataset.inquiryGeneratedError === 'true') {
                error.remove();
            } else if (error) {
                error.hidden = true;
                error.textContent = '';
            }
        },

        clearAllErrors() {
            this.$el.querySelectorAll('input, textarea, select').forEach((field) => this.clearFieldError(field));
            this.errors = {};
        },

        fieldForServerKey(key) {
            const exact = this.$el.querySelector(`[name="${CSS.escape(key)}"]`);

            if (exact) {
                return exact;
            }

            const bracketed = key.replace(/\.([^.]+)/g, '[$1]');

            return this.$el.querySelector(`[name="${CSS.escape(bracketed)}"]`);
        },

        applyServerErrors(errors) {
            this.clearAllErrors();

            Object.entries(errors).forEach(([key, messages]) => {
                const field = this.fieldForServerKey(key);

                if (!field) {
                    return;
                }

                this.touched.add(field.name);
                this.setFieldError(field, Array.isArray(messages) ? messages[0] : messages);
            });
        },

        firstInvalidField() {
            return this.$el.querySelector('[aria-invalid="true"]');
        },

        successTitle() {
            const configuredTitle = this.$el.dataset.successTitle;

            if (configuredTitle) {
                return configuredTitle;
            }

            const sourcePage = this.$el.querySelector('[name="source_page"]')?.value;

            return successTitles[sourcePage] || 'Inquiry received';
        },

        showNotification(type, title, message) {
            this.$dispatch('inquiry-notification', { type, title, message });
        },

        resetAfterSuccess() {
            this.$el.reset();
            this.clearAllErrors();
            this.touched.clear();
            this.fulfillmentType = this.$el.dataset.defaultFulfillment || 'pickup';
            this.$dispatch('inquiry-form:reset', { fulfillmentType: this.fulfillmentType });
        },

        async submit() {
            if (this.submitting) {
                return;
            }

            if (!this.validateAll()) {
                this.firstInvalidField()?.focus();
                this.showNotification('error', 'Please review your inquiry', 'Correct the highlighted fields before submitting.');

                return;
            }

            this.submitting = true;

            try {
                let response;

                try {
                    response = await fetch(this.$el.action, {
                        method: this.$el.method || 'POST',
                        body: new FormData(this.$el),
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                    });
                } catch (error) {
                    this.showNotification('error', 'Unable to connect', 'Please check your connection and try again.');

                    return;
                }

                const payload = await response.json().catch(() => null);

                if (response.status === 422) {
                    this.applyServerErrors(payload?.errors ?? {});
                    this.showNotification('error', 'Please review your inquiry', payload?.message ?? 'Correct the highlighted fields and try again.');
                    this.firstInvalidField()?.focus();

                    return;
                }

                if (response.status === 419) {
                    this.showNotification('error', 'Session expired', 'Refresh the page and submit your inquiry again.');

                    return;
                }

                if (response.status === 429) {
                    this.showNotification('error', 'Please try again shortly', 'Too many requests were received. Your inquiry has not been submitted.');

                    return;
                }

                if (!response.ok || payload?.success !== true || typeof payload.message !== 'string') {
                    this.showNotification('error', 'Unable to process inquiry', 'The server could not process your inquiry. Your information has been preserved; please try again.');

                    return;
                }

                this.resetAfterSuccess();
                this.showNotification('success', this.successTitle(), payload.message);
            } finally {
                this.submitting = false;
            }
        },
    };
}
