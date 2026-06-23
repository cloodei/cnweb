<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('locations.index');
    }

    public function manage(): View
    {
        $categories = Category::withCount('locations')->orderBy('name')->get();

        return view('admin.categories', compact('categories'));
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

        return redirect()->route('admin.categories')->with('success', 'Đã thêm danh mục thành công!');
    }

    public function show(Category $category): RedirectResponse
    {
        return redirect()->route('locations.index');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Danh mục này đã tồn tại.',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->locations()->exists()) {
            return redirect()
                ->route('admin.categories')
                ->with('error', 'Không thể xóa danh mục đang có địa điểm. Hãy chuyển hoặc xóa các địa điểm trước.');
        }

        $category->delete($category->id);

        return redirect()->route('admin.categories')->with('success', 'Đã xóa danh mục!');
    }
}
