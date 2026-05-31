<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index()
    {
        
        $categories = Category::latest()->get(); 
        return view('categories.index', compact('categories'));
    }

    
    public function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Danh mục này đã tồn tại.',
        ]);

      
        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Đã thêm danh mục thành công!');
    }
    
    public function show(Category $category)
    {
        
        $locations = $category->locations()->latest()->get();
        return view('categories.show', compact('category', 'locations'));
    }

    
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Đã xóa danh mục!');
    }
}