<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class SubscriptionController extends Controller
{
    private function apiBase(): string
    {
        return rtrim(config('app.api_url'), '/');
    }

    private function fetchList(string $path): array
    {
        try {
            $response = Http::acceptJson()->timeout(15)->retry(2, 250)->get($this->apiBase() . '/' . ltrim($path, '/'));
        } catch (Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $payload = $response->json();

        if (isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }

        return is_array($payload) ? $payload : [];
    }

    private function decorateSubscriptions(array $subscriptions, array $customers, array $services): array
    {
        $customerMap = collect($customers)->keyBy('id');
        $serviceMap = collect($services)->keyBy('id');

        return array_map(function (array $subscription) use ($customerMap, $serviceMap) {
            $subscription['customer_name'] = data_get($subscription, 'customer.name')
                ?? data_get($customerMap->get(data_get($subscription, 'customer_id')), 'name')
                ?? '-';

            $subscription['service_name'] = data_get($subscription, 'service.name')
                ?? data_get($subscription, 'service.service_name')
                ?? data_get($serviceMap->get(data_get($subscription, 'service_id')), 'name')
                ?? data_get($serviceMap->get(data_get($subscription, 'service_id')), 'service_name')
                ?? '-';

            $startDate = data_get($subscription, 'start_date');
            $endDate = data_get($subscription, 'end_date');

            $subscription['start_date_formatted'] = $startDate
                ? Carbon::parse($startDate)->isoFormat('D MMM YYYY')
                : '-';

            $subscription['end_date_formatted'] = $endDate
                ? Carbon::parse($endDate)->isoFormat('D MMM YYYY')
                : '-';

            $subscription['period_label'] = trim($subscription['start_date_formatted'] . ' - ' . $subscription['end_date_formatted']);

            return $subscription;
        }, $subscriptions);
    }

    public function index(Request $request)
    {
        $customers = $this->fetchList('/customers');
        $services = $this->fetchList('/services');
        $subscriptions = $this->decorateSubscriptions($this->fetchList('/subscriptions'), $customers, $services);
        $editingSubscription = $request->filled('edit')
            ? collect($subscriptions)->firstWhere('id', $request->integer('edit'))
            : null;

        return view('subscriptions', compact('subscriptions', 'customers', 'services', 'editingSubscription'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required'],
            'service_id' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:active,trial,isolir,dismantle'],
        ]);

        $response = Http::acceptJson()->post($this->apiBase() . '/subscriptions', $validated);

        if ($response->failed()) {
            return back()->withInput()->withErrors([
                'api' => 'Failed to create subscription.',
            ]);
        }

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function update(Request $request, string $subscription)
    {
        $validated = $request->validate([
            'customer_id' => ['required'],
            'service_id' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:active,trial,isolir,dismantle'],
        ]);

        $response = Http::acceptJson()->put($this->apiBase() . '/subscriptions/' . $subscription, $validated);

        if ($response->failed()) {
            return back()->withInput()->withErrors([
                'api' => 'Failed to update subscription.',
            ]);
        }

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(string $subscription)
    {
        $response = Http::acceptJson()->delete($this->apiBase() . '/subscriptions/' . $subscription);

        if ($response->failed()) {
            return back()->withErrors([
                'api' => 'Failed to delete subscription.',
            ]);
        }

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }
}
