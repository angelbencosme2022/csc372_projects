/**
 * checkout.js — Stripe Elements payment form for 401 Thrift
 *
 * Requires:
 *   - https://js.stripe.com/v3/ loaded before this script
 *   - js/config.js loaded before this script (defines STRIPE_PUBLISHABLE_KEY)
 *   - #card-element, #card-errors, #payment-form, #submit-payment,
 *     #button-text, #spinner, #payment-message, #order-total-cents
 *     all present in the DOM.
 */

(function () {
    'use strict';

    const isDemoMode = !STRIPE_PUBLISHABLE_KEY || STRIPE_PUBLISHABLE_KEY.includes('YOUR_KEY_HERE');
    const form = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const msgEl = document.getElementById('payment-message');
    const totalCents = parseInt(document.getElementById('order-total-cents').value, 10);

    let stripe = null;
    let cardElement = null;

    if (!isDemoMode) {
        stripe = Stripe(STRIPE_PUBLISHABLE_KEY);
        const elements = stripe.elements();

        const style = {
            base: {
                fontSize: '15px',
                color: '#2e2416',
                fontFamily: "'DM Sans', system-ui, sans-serif",
                '::placeholder': { color: '#8a7a62' },
            },
            invalid: { color: '#c0431a', iconColor: '#c0431a' },
        };

        cardElement = elements.create('card', { style, hidePostalCode: true });
        cardElement.mount('#card-element');

        cardElement.on('change', ({ error }) => {
            const display = document.getElementById('card-errors');
            display.textContent = error ? error.message : '';
        });
    } else {
        const cardElementContainer = document.getElementById('card-element');
        cardElementContainer.textContent = 'Demo mode active. Card details are not required for this milestone build.';
        cardElementContainer.classList.add('stripe-element', 'stripe-element-demo');
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Basic HTML5 validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        setLoading(true);
        clearMessage();

        // Collect billing details from the form
        const name    = document.getElementById('customer-name').value.trim();
        const email   = document.getElementById('customer-email').value.trim();
        const line1   = document.getElementById('address-line1').value.trim();
        const line2   = document.getElementById('address-line2').value.trim();
        const city    = document.getElementById('city').value.trim();
        const state   = document.getElementById('state').value.trim();
        const zip     = document.getElementById('zip').value.trim();

        if (isDemoMode) {
            await submitOrder({
                name,
                email,
                address_line1: line1,
                address_line2: line2,
                city,
                state,
                postal_code: zip,
                payment_method_id: '',
            });
            return;
        }

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name,
                email,
                address: { line1, line2, city, state, postal_code: zip, country: 'US' },
            },
        });

        if (error) {
            showMessage(error.message, 'error');
            setLoading(false);
            return;
        }

        await submitOrder({
            name,
            email,
            address_line1: line1,
            address_line2: line2,
            city,
            state,
            postal_code: zip,
            payment_method_id: paymentMethod.id,
        });
    });

    // ── Helpers ───────────────────────────────────────────────────────────────
    function setLoading(isLoading) {
        submitBtn.disabled = isLoading;
        buttonText.classList.toggle('hidden', isLoading);
        spinner.classList.toggle('hidden', !isLoading);
    }

    function showMessage(text, type) {
        msgEl.textContent = text;
        msgEl.className   = 'payment-message ' + (type || '');
    }

    function clearMessage() {
        msgEl.textContent = '';
        msgEl.className   = 'payment-message';
    }

    async function submitOrder(payload) {
        try {
            const response = await fetch('api/checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();
            if (!response.ok || !result.success) {
                const errors = result.errors ? Object.values(result.errors).join(' ') : '';
                showMessage(errors || result.error || 'Checkout failed. Please try again.', 'error');
                setLoading(false);
                return;
            }

            showMessage('Order saved successfully. Redirecting...', 'success');
            window.location.href = result.redirect_url || 'order-confirmation.php';
        } catch (error) {
            showMessage('Server error while saving your order. Please try again.', 'error');
            setLoading(false);
        }
    }
})();
