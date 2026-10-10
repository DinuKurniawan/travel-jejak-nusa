<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query()->published()->orderBy('sort_order')->orderBy('id');

        if ($category = trim((string) $request->string('category'))) {
            $query->where('category', $category);
        }
        if ($search = trim((string) $request->string('search'))) {
            $query->where(fn ($q) => $q->where('question', 'like', "%{$search}%")->orWhere('answer', 'like', "%{$search}%"));
        }

        return $query->get();
    }

    public function adminIndex()
    {
        return Faq::query()->orderBy('sort_order')->orderBy('id')->paginate(20);
    }

    public function store(Request $request)
    {
        return response()->json(Faq::create($this->validated($request)), 201);
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request, true));

        return $faq;
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return response()->noContent();
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, bool $sometimes = false): array
    {
        $req = $sometimes ? 'sometimes' : 'required';

        return $request->validate([
            'category' => [$req, 'string', 'max:100'],
            'question' => [$req, 'string', 'max:500'],
            'answer' => [$req, 'string'],
            'payload' => ['sometimes', 'nullable', 'array'],
            'status' => ['sometimes', 'string', Rule::in(['draft', 'published'])],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);
    }
}
