<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class CustomerController extends Controller
{
    private function apiBase(): string
    {
        return rtrim(config('app.api_url'), '/');
    }

    private function fetchCustomers(): array
    {
        try {
            $response = Http::acceptJson()->timeout(15)->retry(2, 250)->get($this->apiBase() . '/customers');
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

    public function index(Request $request)
    {
        $customers = $this->fetchCustomers();
        $editingCustomer = $request->filled('edit')
            ? collect($customers)->firstWhere('id', $request->integer('edit'))
            : null;

        return view('dashboard', compact('customers', 'editingCustomer'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $response = Http::acceptJson()->post($this->apiBase() . '/customers', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => $validated['status'] === 'active',
        ]);

        if ($response->failed()) {
            return back()->withInput()->withErrors([
                'api' => 'Failed to create customer.',
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function update(Request $request, string $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $response = Http::acceptJson()->put($this->apiBase() . '/customers/' . $customer, [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => $validated['status'] === 'active',
        ]);

        if ($response->failed()) {
            return back()->withInput()->withErrors([
                'api' => 'Failed to update customer.',
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(string $customer)
    {
        $response = Http::acceptJson()->delete($this->apiBase() . '/customers/' . $customer);

        if ($response->failed()) {
            return back()->withErrors([
                'api' => 'Failed to delete customer.',
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
