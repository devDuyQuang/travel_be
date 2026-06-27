<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::query()->orderBy('sort_order')->orderBy('id')->paginate(30);
        return view('faq.main', compact('faqs'));
    }

    public function create()
    {
        return view('faq.form', ['faq' => new Faq()]);
    }

    public function store(Request $request)
    {
        Faq::create($this->validated($request));
        return redirect()->to(panel_route('faq.index'))->with('success', 'Đã tạo FAQ.');
    }

    public function edit($domain, Faq $faq)
    {
        return view('faq.form', compact('faq'));
    }

    public function update(Request $request, $domain, Faq $faq)
    {
        $faq->update($this->validated($request));
        return redirect()->to(panel_route('faq.index'))->with('success', 'Đã cập nhật FAQ.');
    }

    public function destroy($domain, Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'Đã xóa FAQ.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category' => ['nullable', 'string', 'max:255'],
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        return $data;
    }
}
