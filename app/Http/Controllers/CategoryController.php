<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('locations')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Danh mục này đã tồn tại.',
        ]);

        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Đã thêm danh mục thành công!');
    }

    public function show(Category $category): View
    {
        $locations = $category->locations()->latest()->get();
        return view('categories.show', compact('category', 'locations'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->locations()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Không thể xóa danh mục đang có địa điểm. Hãy chuyển hoặc xóa các địa điểm trước.');
        }

        $category->delete($category->id);
        return redirect()->route('categories.index')->with('success', 'Đã xóa danh mục!');
    }
}
