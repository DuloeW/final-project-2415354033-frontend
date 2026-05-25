@props([
    'id' => 'service-modal',
    'action' => null,
    'service' => null,
])

<dialog id="{{ $id }}" @if($service || $errors->any()) open @endif class="fixed left-1/2 top-1/2 m-0 w-[min(92vw,44rem)] max-h-[90vh] -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-3xl border border-slate-200 bg-white p-0 text-slate-800 shadow-2xl">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 text-center">
                <h2 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $service ? 'Edit Service' : 'Add Services' }}</h2>
            </div>

            <button type="button" data-close-dialog class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Close modal">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5l9 9M13.5 4.5l-9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ $action ?? route('services.store') }}" class="mt-6 space-y-5">
            @csrf
            @if($service)
                @method('PUT')
            @endif

            <div>
                <label for="service-name" class="mb-2 block text-lg font-semibold text-slate-800">Service Name</label>
                <input id="service-name" name="name" type="text" value="{{ old('name', data_get($service, 'name')) }}" placeholder="Enter service name" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="service-price" class="mb-2 block text-lg font-semibold text-slate-800">Price</label>
                <input id="service-price" name="price" type="text" value="{{ old('price', data_get($service, 'price')) }}" placeholder="Enter service price" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="service-description" class="mb-2 block text-lg font-semibold text-slate-800">Description</label>
                <textarea id="service-description" name="description" rows="4" placeholder="Enter service description" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">{{ old('description', data_get($service, 'description')) }}</textarea>
            </div>

            <div>
                <label for="service-status" class="mb-2 block text-lg font-semibold text-slate-800">Status</label>
                <div class="relative">
                    <select id="service-status" name="status" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200">
                        <option value="" disabled @selected(old('status', data_get($service, 'status') === null ? '' : (data_get($service, 'status') ? 'active' : 'inactive')) === '')>Select Status</option>
                        <option value="active" @selected(old('status', data_get($service, 'status') ? 'active' : 'inactive') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', data_get($service, 'status') ? 'active' : 'inactive') === 'inactive')>Inactive</option>
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
                        {{ $service ? 'Save Changes' : 'Submit' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</dialog>