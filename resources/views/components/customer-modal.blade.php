@props([
    'id' => 'customer-modal',
    'action' => null,
    'customer' => null,
])

<dialog id="{{ $id }}" @if($customer || $errors->any()) open @endif class="fixed left-1/2 top-1/2 m-0 w-[min(92vw,44rem)] max-h-[90vh] -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-3xl border border-slate-200 bg-white p-0 text-slate-800 shadow-2xl">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 text-center">
                <h2 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $customer ? 'Edit Customer' : 'Add Customer' }}</h2>
            </div>

            <button type="button" data-close-dialog class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Close modal">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5l9 9M13.5 4.5l-9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ $action ?? route('customers.store') }}" class="mt-6 space-y-5">
            @csrf
            @if($customer)
                @method('PUT')
            @endif

            <div>
                <label for="customer-name" class="mb-2 block text-lg font-semibold text-slate-800">Customer Name</label>
                <input id="customer-name" name="name" type="text" value="{{ old('name', data_get($customer, 'name')) }}" placeholder="Enter customer name" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="customer-email" class="mb-2 block text-lg font-semibold text-slate-800">Email</label>
                <input id="customer-email" name="email" type="email" value="{{ old('email', data_get($customer, 'email')) }}" placeholder="Enter customer email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="customer-phone" class="mb-2 block text-lg font-semibold text-slate-800">Phone</label>
                <input id="customer-phone" name="phone" type="text" value="{{ old('phone', data_get($customer, 'phone')) }}" placeholder="Enter customer phone" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="customer-address" class="mb-2 block text-lg font-semibold text-slate-800">Address</label>
                <input id="customer-address" name="address" type="text" value="{{ old('address', data_get($customer, 'address')) }}" placeholder="Enter customer address" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="customer-status" class="mb-2 block text-lg font-semibold text-slate-800">Status</label>
                <div class="relative">
                    <select id="customer-status" name="status" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
                        <option value="" disabled @selected(old('status', data_get($customer, 'status') === null ? '' : (data_get($customer, 'status') ? 'active' : 'inactive')) === '')>Select Status</option>
                        <option value="active" @selected(old('status', data_get($customer, 'status') ? 'active' : 'inactive') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', data_get($customer, 'status') ? 'active' : 'inactive') === 'inactive')>Inactive</option>
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
                        {{ $customer ? 'Save Changes' : 'Submit' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</dialog>