<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'ERP Dashboard') }} - Subscriptions</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            dialog::backdrop {
                background: rgba(15, 23, 42, 0.58);
                backdrop-filter: blur(4px);
            }
        </style>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-cyan-100/70 blur-3xl"></div>
            <div class="absolute right-0 top-20 h-80 w-80 rounded-full bg-emerald-100/70 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-slate-200/70 blur-3xl"></div>

            <div class="relative flex min-h-screen">
                <x-sidebar active="subscription" />

                <main class="flex-1 px-4 py-5 sm:px-6 lg:px-8">
                    <div class="mx-auto flex max-w-7xl flex-col gap-6">
                        <header class="rounded-2xl border border-white/80 bg-white/80 px-5 py-4 shadow-sm backdrop-blur">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">ERP / Subscriptions</p>
                                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Subscriptions</h1>
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="button" data-open-dialog="subscription-modal" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700">
                                        <span class="text-base leading-none">+</span>
                                        <span>Add Data</span>
                                    </button>
                                </div>
                            </div>
                        </header>

                        <section class="overflow-visible rounded-2xl border border-slate-200/70 bg-white/90 shadow-sm backdrop-blur">
                            <div class="border-b border-slate-200/70 px-5 py-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h2 class="text-lg font-semibold text-slate-900">Subscription List</h2>
                                        <p class="mt-1 text-sm text-slate-500">Ringkasan subscription mengikuti desain referensi.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto overflow-y-visible">
                                <table class="min-w-full divide-y divide-slate-200/80 text-left text-sm">
                                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wide text-slate-500">
                                        <tr>
                                            <th class="px-5 py-4 font-medium">Customer Name</th>
                                            <th class="px-5 py-4 font-medium">Services</th>
                                            <th class="px-5 py-4 font-medium">Services Period</th>
                                            <th class="px-5 py-4 font-medium">Status</th>
                                            {{-- <th class="px-5 py-4 font-medium text-right">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200/70">
                                        @forelse ($subscriptions as $index => $subscription)
                                            @php
                                                $status = data_get($subscription, 'status', 'active');
                                                $statusLabel = ucfirst($status);
                                                $statusClass = match ($status) {
                                                    'trial' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
                                                    'isolir' => 'bg-rose-100 text-rose-700 ring-1 ring-rose-200',
                                                    'dismantle' => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
                                                    default => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
                                                };
                                            @endphp
                                            <tr class="hover:bg-slate-50/70">
                                                <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900">{{ data_get($subscription, 'customer_name', '-') }}</td>
                                                <td class="whitespace-nowrap px-5 py-4 text-slate-700">{{ data_get($subscription, 'service_name', '-') }}</td>
                                                <td class="whitespace-nowrap px-5 py-4 text-slate-700">
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-medium text-slate-900">{{ data_get($subscription, 'period_label', '-') }}</span>
                                                        <span class="text-xs text-slate-500">{{ data_get($subscription, 'start_date_formatted', '-') }} sampai {{ data_get($subscription, 'end_date_formatted', '-') }}</span>
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-4">
                                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                                                </td>
                                                {{-- <td class="whitespace-nowrap px-5 py-4 text-right">
                                                    <button type="button" data-action-menu-trigger="subscription-action-{{ $index }}" class="inline-flex rounded-lg px-2 py-1 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline-block">
                                                            <path d="M4 9H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                            <path d="M4 5H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                            <path d="M4 13H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        </svg>
                                                    </button>
                                                </td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">No subscriptions found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>

        @foreach ($subscriptions as $index => $subscription)
            <div id="subscription-action-{{ $index }}" data-action-menu-panel data-open="false" class="fixed z-[100] hidden w-72 rounded-2xl border border-slate-200 bg-white p-3 shadow-[0_18px_40px_rgba(15,23,42,0.12)]" style="top: 0; left: 0;">
                <div class="space-y-1">
                    <form method="POST" action="{{ route('subscriptions.update', data_get($subscription, 'id')) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="customer_id" value="{{ data_get($subscription, 'customer_id') }}">
                        <input type="hidden" name="service_id" value="{{ data_get($subscription, 'service_id') }}">
                        <input type="hidden" name="start_date" value="{{ data_get($subscription, 'start_date') }}">
                        <input type="hidden" name="end_date" value="{{ data_get($subscription, 'end_date') }}">
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="flex w-full items-center gap-4 rounded-xl px-4 py-3 text-[17px] font-medium text-slate-700 transition hover:bg-slate-100">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-900">
                                <path d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M12 4V20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="2" opacity="0.9"/>
                            </svg>
                            <span>Active</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('subscriptions.update', data_get($subscription, 'id')) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="customer_id" value="{{ data_get($subscription, 'customer_id') }}">
                        <input type="hidden" name="service_id" value="{{ data_get($subscription, 'service_id') }}">
                        <input type="hidden" name="start_date" value="{{ data_get($subscription, 'start_date') }}">
                        <input type="hidden" name="end_date" value="{{ data_get($subscription, 'end_date') }}">
                        <input type="hidden" name="status" value="dismantle">
                        <button type="submit" class="flex w-full items-center gap-4 rounded-xl px-4 py-3 text-[17px] font-medium text-slate-700 transition hover:bg-slate-100">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-900">
                                <path d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8.5 8.5L15.5 15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M15.5 8.5L8.5 15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>Deactivate</span>
                        </button>
                    </form>

                    <a href="{{ route('subscriptions.index', ['edit' => data_get($subscription, 'id')]) }}" class="flex items-center gap-4 rounded-xl px-4 py-3 text-[17px] font-medium text-slate-700 transition hover:bg-slate-100">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-900">
                            <path d="M4 20H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M7 17L17 7L19 9L9 19H7V17Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                        <span>Edit</span>
                    </a>

                    <form method="POST" action="{{ route('subscriptions.destroy', data_get($subscription, 'id')) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex w-full items-center gap-4 rounded-xl px-4 py-3 text-[17px] font-medium text-red-600 transition hover:bg-red-50">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-red-600">
                                <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 6V4H16V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 6L8 20H16L17 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M10 10V16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M14 10V16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

        <x-subscription-modal :action="$editingSubscription ? route('subscriptions.update', data_get($editingSubscription, 'id')) : route('subscriptions.store')" :subscription="$editingSubscription" :customers="$customers" :services="$services" />
    </body>
</html>