<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query()->published()->orderBy('sort_order')->orderByDesc('id');

        if ($search = trim((string) $request->string('search'))) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('location', 'like', "%{$search}%"));
        }
        if ($region = trim((string) $request->string('region'))) {
            $query->where('region', $region);
        }
        if ($type = trim((string) $request->string('type'))) {
            $query->where('trip_type', $type);
        }

        return $query->paginate($request->integer('per_page', 12));
    }

    public function featured()
    {
        return Package::query()->published()->featured()->orderBy('sort_order')->take(6)->get();
    }

    public function show(Request $request, Package $package)
    {
        if ($package->status !== 'published' && ! $request->user()) {
            abort(404);
        }

        return $package;
    }

    public function adminIndex()
    {
        return Package::query()->orderBy('sort_order')->orderByDesc('id')->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($data);

        return response()->json(Package::create($data), 201);
    }

    public function update(Request $request, Package $package)
    {
        $data = $this->validated($request, $package->id);
        if (array_key_exists('title', $data) && empty($data['slug'] ?? null)) {
            $data['slug'] = $this->slug($data, $package->id);
        }
        $package->update($data);

        return $package;
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return response()->noContent();
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('packages', 'slug')->ignore($ignoreId)],
            'location' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:255'],
            'region' => ['sometimes', 'nullable', 'string', 'max:100'],
            'duration' => ['sometimes', 'nullable', 'string', 'max:100'],
            'trip_type' => ['sometimes', 'string', Rule::in(['open', 'private'])],
            'price' => [$ignoreId ? 'sometimes' : 'required', 'integer', 'min:0'],
            'price_suffix' => ['sometimes', 'string', 'max:20'],
            'badge' => ['sometimes', 'nullable', 'string', 'max:100'],
            'seat_badge' => ['sometimes', 'nullable', 'string', 'max:100'],
            'rating' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'reviews_count' => ['sometimes', 'integer', 'min:0'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'excerpt' => ['sometimes', 'nullable', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::in(['draft', 'published', 'archived'])],
            'featured' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);
    }

    /** @param array<string, mixed> $data */
    private function slug(array $data, ?int $ignoreId = null): string
    {
        $base = Str::slug($data['slug'] ?? $data['title'] ?? 'paket');
        $slug = $base;
        $i = 2;
        while (Package::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
