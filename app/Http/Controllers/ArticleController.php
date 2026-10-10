<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query()->published()->orderByDesc('id');

        if ($search = trim((string) $request->string('search'))) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('excerpt', 'like', "%{$search}%"));
        }
        if ($category = trim((string) $request->string('category'))) {
            $query->where('category', $category);
        }

        return $query->paginate($request->integer('per_page', 9));
    }

    public function show(Request $request, Article $article)
    {
        if ($article->status !== 'published' && ! $request->user()) {
            abort(404);
        }

        return $article;
    }

    public function adminIndex()
    {
        return Article::query()->orderByDesc('id')->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['slug'] ?? $data['title']);

        return response()->json(Article::create($data), 201);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($this->validated($request, $article->id));

        return $article;
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return response()->noContent();
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($ignoreId)],
            'category' => [$ignoreId ? 'sometimes' : 'required', 'string', 'max:100'],
            'author' => ['sometimes', 'nullable', 'string', 'max:255'],
            'read_time' => ['sometimes', 'nullable', 'string', 'max:50'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'excerpt' => ['sometimes', 'nullable', 'string'],
            'body' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::in(['draft', 'published', 'archived'])],
            'featured' => ['sometimes', 'boolean'],
        ]);
    }
}
