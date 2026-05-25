@props(['active' => 'customers'])

@php
    $items = [
        ['key' => 'customers', 'label' => 'Customers', 'route' => 'customers.index', 'icon' => 'M4 5h10v1.5H4zM4 8.75h10v1.5H4zM4 12.5h10V14H4z'],
        ['key' => 'services', 'label' => 'Services', 'route' => 'services.index', 'icon' => 'M7 3.5l5.5 3v5L7 14.5 1.5 11.5v-5L7 3.5Z'],
        ['key' => 'subscription', 'label' => 'Subscription', 'route' => 'subscriptions.index', 'icon' => 'M3 4h8v8H3zM11 6h2.5l1.5 1.5V12H11z'],
    ];
@endphp

<aside
    class="flex w-[260px] shrink-0 flex-col border-r border-slate-200/80 bg-white/90 px-4 py-5 shadow-sm backdrop-blur lg:w-[280px]">
    <div class="flex items-center justify-between gap-3 px-1">
        <div class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-sm font-semibold text-white shadow-sm">
                ERP</div>
            <div>
                <p class="text-sm font-semibold tracking-tight text-slate-900">ERP</p>
                <p class="text-xs text-slate-500">Admin panel</p>
            </div>
        </div>

        <button type="button" class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
            aria-label="Toggle sidebar">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.5 4h9M3.5 8h9M3.5 12h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    <nav class="mt-8 flex flex-1 flex-col gap-2">
        @foreach ($items as $item)
            @php
                $isActive = $active === $item['key'];
            @endphp

            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ $isActive ? 'bg-slate-100 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="shrink-0">
                    <path d="{{ $item['icon'] }}" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

</aside>