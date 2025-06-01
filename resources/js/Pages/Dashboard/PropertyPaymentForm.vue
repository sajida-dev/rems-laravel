<template>
    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mx-auto max-w-5xl">
                <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">Payment</h2>

                <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12">
                    <!-- Payment Form -->
                    <form @submit.prevent="submitPayment"
                        class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6 lg:max-w-xl lg:p-8">
                        <input type="hidden" v-model="form.property_id" name="property_id" />
                        <input type="hidden" v-model="form.amount" name="amount" />

                        <div class="mb-6 grid grid-cols-2 gap-4 text-sm">
                            <!-- Full Name -->
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block mb-1 font-medium text-gray-700">Full Name</label>
                                <input v-model.trim="form.full_name" type="text"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2"
                                    :class="{ 'border-red-500': form.errors.full_name }" required />
                                <p v-if="form.errors.full_name" class="mt-1 text-xs text-red-600">{{
                                    form.errors.full_name }}</p>
                            </div>

                            <!-- Card Element -->
                            <div class="col-span-2">
                                <label class="block mb-1 font-medium text-gray-700">Card Details</label>
                                <div id="card-element" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2"
                                    :class="{ 'border-red-500': cardError }"></div>
                                <p v-if="cardError" class="mt-1 text-xs text-red-600">{{ cardError }}</p>
                            </div>

                            <!-- Payment Type -->
                            <div class="col-span-2">
                                <label class="block mb-1 font-medium text-gray-700">Payment Type</label>
                                <select v-model="form.payment_type"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2"
                                    :class="{ 'border-red-500': form.errors.payment_type }">
                                    <option value="rent">Rent</option>
                                    <option value="buy">Buy</option>
                                </select>
                                <p v-if="form.errors.payment_type" class="mt-1 text-xs text-red-600">{{
                                    form.errors.payment_type }}</p>
                            </div>
                        </div>

                        <button type="submit" :disabled="processing"
                            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ processing ? 'Processing…' : 'Confirm & Pay' }}
                        </button>
                    </form>

                    <!-- Order Summary -->
                    <div class="mt-6 grow sm:mt-8 lg:mt-0">
                        <div class="space-y-4 rounded-lg border border-gray-100 bg-gray-50 p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <span>Property</span>
                                    <span>{{ property.title }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Type</span>
                                    <span>{{ type === 'buy' ? 'Purchase' : 'Rent' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Amount</span>
                                    <span>${{ amount.toFixed(2) }}</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-base font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>${{ amount.toFixed(2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mt-6 flex justify-center gap-8">
                            <img class="h-8" src="/images/payment/visa.svg" alt="Visa" />
                            <img class="h-8" src="/images/payment/mastercard.svg" alt="Mastercard" />
                            <img class="h-8" src="/images/payment/amex.svg" alt="American Express" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { loadStripe } from '@stripe/stripe-js'

defineOptions({ layout: DashboardLayout })

// Props from Inertia
const { property, price, stripeKey, clientSecret } = usePage().props

// Form state
const form = useForm({
    property_id: property.id,
    full_name: '',
    payment_type: 'rent',
    amount: price
})

const processing = ref(false)
const cardError = ref(null)
let stripe, elements, card

// Reactive computed amount
const amount = computed(() => form.amount)

onMounted(async () => {
    try {
        // Initialize Stripe
        stripe = await loadStripe(stripeKey)
        elements = stripe.elements({
            clientSecret,
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#3B82F6',
                    colorBackground: '#F9FAFB',
                    colorText: '#1F2937',
                    colorDanger: '#EF4444',
                    fontFamily: 'ui-sans-serif, system-ui, -apple-system, sans-serif',
                    spacingUnit: '4px',
                    borderRadius: '8px'
                }
            }
        })

        // Create card element
        card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#1F2937',
                    '::placeholder': {
                        color: '#9CA3AF'
                    }
                }
            }
        })

        // Mount card element
        card.mount('#card-element')

        // Handle card errors
        card.on('change', e => {
            cardError.value = e.error ? e.error.message : null
        })
    } catch (error) {
        console.error('Failed to initialize Stripe:', error)
        toast.error('Failed to initialize payment system. Please try again later.')
    }
})

async function submitPayment() {
    // Reset errors
    cardError.value = null
    form.clearErrors()

    // Front-end validation
    if (!form.full_name) {
        form.setError('full_name', 'Name is required.')
        return
    }

    if (!['rent', 'buy'].includes(form.payment_type)) {
        form.setError('payment_type', 'Invalid payment type.')
        return
    }

    processing.value = true

    try {
        // Confirm card payment
        const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(
            clientSecret,
            {
                payment_method: {
                    card,
                    billing_details: {
                        name: form.full_name
                    }
                }
            }
        )

        if (stripeError) {
            throw new Error(stripeError.message)
        }

        // Submit payment to server
        form.post(route('payment.store'), {
            onSuccess: () => {
                toast.success('Payment completed successfully!')
            },
            onError: (errors) => {
                toast.error('Payment failed: ' + (errors.error || 'Unknown error'))
            },
            preserveState: true
        })
    } catch (error) {
        console.error('Payment error:', error)
        toast.error(error.message || 'Payment failed. Please try again.')
    } finally {
        processing.value = false
    }
}
</script>

<style>
.StripeElement {
    box-sizing: border-box;
    height: 40px;
    padding: 10px 12px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    background-color: white;
    transition: box-shadow 150ms ease;
}

.StripeElement--focus {
    box-shadow: 0 1px 3px 0 #CFD7DF;
}

.StripeElement--invalid {
    border-color: #EF4444;
}

.StripeElement--webkit-autofill {
    background-color: #FEFDE5 !important;
}
</style>
