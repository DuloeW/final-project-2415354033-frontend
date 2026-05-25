@props([
    'id' => 'subscription-modal',
    'action' => null,
    'subscription' => null,
    'customers' => [],
    'services' => [],
])

@php
    $startDateValue = old('start_date');
    $endDateValue = old('end_date');

    if (! $startDateValue && data_get($subscription, 'start_date')) {
        $startDateValue = \Illuminate\Support\Carbon::parse(data_get($subscription, 'start_date'))->format('Y-m-d');
    }

    if (! $endDateValue && data_get($subscription, 'end_date')) {
        $endDateValue = \Illuminate\Support\Carbon::parse(data_get($subscription, 'end_date'))->format('Y-m-d');
    }
@endphp

<dialog id="{{ $id }}" @if($subscription || $errors->any()) open @endif class="fixed left-1/2 top-1/2 m-0 w-[min(92vw,44rem)] max-h-[90vh] -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-3xl border border-slate-200 bg-white p-0 text-slate-800 shadow-2xl">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 text-center">
                <h2 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $subscription ? 'Edit Subscription' : 'Add Subscription' }}</h2>
            </div>

            <button type="button" data-close-dialog class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Close modal">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5l9 9M13.5 4.5l-9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ $action ?? route('subscriptions.store') }}" class="mt-6 space-y-5">
            @csrf
            @if($subscription)
                @method('PUT')
            @endif

            <div>
                <label for="subscription-customer" class="mb-2 block text-lg font-semibold text-slate-800">Customer</label>
                <div class="relative">
                    <select id="subscription-customer" name="customer_id" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
                        <option value="" disabled @selected(old('customer_id', data_get($subscription, 'customer_id')) === null || old('customer_id', data_get($subscription, 'customer_id')) === '')>Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ data_get($customer, 'id') }}" @selected((string) old('customer_id', data_get($subscription, 'customer_id')) === (string) data_get($customer, 'id'))>
                                {{ data_get($customer, 'name') }}
                            </option>
                        @endforeach
                    </select>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div>
                <label for="subscription-service" class="mb-2 block text-lg font-semibold text-slate-800">Service</label>
                <div class="relative">
                    <select id="subscription-service" name="service_id" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
                        <option value="" disabled @selected(old('service_id', data_get($subscription, 'service_id')) === null || old('service_id', data_get($subscription, 'service_id')) === '')>Select Service</option>
                        @foreach ($services as $service)
                            <option value="{{ data_get($service, 'id') }}" @selected((string) old('service_id', data_get($subscription, 'service_id')) === (string) data_get($service, 'id'))>
                                {{ data_get($service, 'name', data_get($service, 'service_name', '')) }}
                            </option>
                        @endforeach
                    </select>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="subscription-start-date" class="mb-2 block text-lg font-semibold text-slate-800">Start Date</label>
                    <input id="subscription-start-date" name="start_date" type="date" value="{{ $startDateValue }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
                </div>

                <div>
                    <label for="subscription-end-date" class="mb-2 block text-lg font-semibold text-slate-800">End Date</label>
                    <input id="subscription-end-date" name="end_date" type="date" value="{{ $endDateValue }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
                </div>
            </div>

            <div>
                <label for="subscription-status" class="mb-2 block text-lg font-semibold text-slate-800">Status</label>
                <div class="relative">
                    <select id="subscription-status" name="status" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
                        <option value="" disabled @selected(old('status', data_get($subscription, 'status')) === null || old('status', data_get($subscription, 'status')) === '')>Select Status</option>
                        <option value="active" @selected(old('status', data_get($subscription, 'status')) === 'active')>Active</option>
                        <option value="trial" @selected(old('status', data_get($subscription, 'status')) === 'trial')>Trial</option>
                        <option value="isolir" @selected(old('status', data_get($subscription, 'status')) === 'isolir')>Isolir</option>
                        <option value="dismantle" @selected(old('status', data_get($subscription, 'status')) === 'dismantle')>Dismantle</option>
                    </select>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="pt-2">
                <div class="flex items-center justify-end gap-3">
                    <button type="button" data-close-dialog class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-800">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-600">
                        {{ $subscription ? 'Save Changes' : 'Submit' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</dialog>