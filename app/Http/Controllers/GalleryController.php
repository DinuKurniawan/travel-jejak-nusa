<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryItem::query()->published()->orderBy('sort_order')->orderByDesc('id');

        if ($tag = trim((string) $request->string('tag'))) {
            $query->where('tag', $tag);
        }

        return $query->paginate($request->integer('per_page', 12));
    }

    public function adminIndex()
    {
        return GalleryItem::query()->orderByDesc('id')->paginate(20);
    }

    public function store(Request $request)
    {
        return response()->json(GalleryItem::create($this->validated($request)), 201);
    }

    public function update(Request $request, GalleryItem $item)
    {
        $item->update($this->validated($request, true));

        return $item;
    }

    public function destroy(GalleryItem $item)
    {
        $item->delete();

        return response()->noContent();
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, bool $sometimes = false): array
    {
        $req = $sometimes ? 'sometimes' : 'required';

        return $request->validate([
            'title' => [$req, 'string', 'max:255'],
            'image' => [$req, 'string', 'max:255'],
            'region' => ['sometimes', 'nullable', 'string', 'max:100'],
            'tag' => ['sometimes', 'nullable', 'string', 'max:100'],
            'photographer' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::in(['draft', 'published'])],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);
    }
}
