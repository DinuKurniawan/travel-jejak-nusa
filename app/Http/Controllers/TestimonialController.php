<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TestimonialController extends Controller
{
    public function index()
    {
        return Testimonial::query()->approved()->orderBy('sort_order')->orderByDesc('id')->take(12)->get();
    }

    public function adminIndex()
    {
        return Testimonial::query()->orderByDesc('id')->paginate(20);
    }

    public function store(Request $request)
    {
        return response()->json(Testimonial::create($this->validated($request)), 201);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($this->validated($request, true));

        return $testimonial;
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->noContent();
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, bool $sometimes = false): array
    {
        $req = $sometimes ? 'sometimes' : 'required';

        return $request->validate([
            'name' => [$req, 'string', 'max:255'],
            'meta' => ['sometimes', 'nullable', 'string', 'max:255'],
            'quote' => [$req, 'string'],
            'stars' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);
    }
}
