<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class ServiceController extends Controller
{
    private function apiBase(): string
    {
        return rtrim(config('app.api_url'), '/');
    }

    private function fetchServices(): array
    {
        try {
            $response = Http::acceptJson()->timeout(15)->retry(2, 250)->get($this->apiBase() . '/services');
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
        $services = $this->fetchServices();
        $editingService = $request->filled('edit')
            ? collect($services)->firstWhere('id', $request->integer('edit'))
            : null;

        return view('services', compact('services', 'editingService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $response = Http::acceptJson()->post($this->apiBase() . '/services', [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] === 'active',
        ]);

        if ($response->failed()) {
            return back()->withInput()->withErrors([
                'api' => 'Failed to create service.',
            ]);
        }

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function update(Request $request, string $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $response = Http::acceptJson()->put($this->apiBase() . '/services/' . $service, [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] === 'active',
        ]);

        if ($response->failed()) {
            return back()->withInput()->withErrors([
                'api' => 'Failed to update service.',
            ]);
        }

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(string $service)
    {
        $response = Http::acceptJson()->delete($this->apiBase() . '/services/' . $service);

        if ($response->failed()) {
            return back()->withErrors([
                'api' => 'Failed to delete service.',
            ]);
        }

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    public function activate(string $service)
    {
        $response = Http::acceptJson()->patch($this->apiBase() . '/services/' . $service . '/activate');

        if ($response->failed()) {
            return back()->withErrors([
                'api' => 'Failed to activate service.',
            ]);
        }

        return redirect()->route('services.index')->with('success', 'Service activated successfully.');
    }

    public function deactivate(string $service)
    {
        $response = Http::acceptJson()->patch($this->apiBase() . '/services/' . $service . '/deactivate');

        if ($response->failed()) {
            return back()->withErrors([
                'api' => 'Failed to deactivate service.',
            ]);
        }

        return redirect()->route('services.index')->with('success', 'Service deactivated successfully.');
    }
}
