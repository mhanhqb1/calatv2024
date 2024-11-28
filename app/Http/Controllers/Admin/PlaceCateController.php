<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstantCommon;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryPlace;
use Illuminate\Http\Request;

class PlaceCateController extends Controller
{
    public function index()
    {
        $cates = Category::paginate(ConstantCommon::PAGE_LIMIT);
        return view('admin.place-cates.index', compact('cates'));
    }

    // Hiển thị form tạo mới địa điểm
    public function create()
    {
        return view('admin.place-cates.create');
    }

    // Lưu địa điểm mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.place_cates.index')->with('success', 'Category created successfully.');
    }

    // Hiển thị form chỉnh sửa địa điểm
    public function edit($id)
    {
        $cate = Category::findOrFail($id);
        return view('admin.place-cates.edit', compact('cate'));
    }

    // Cập nhật địa điểm
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $cate = Category::findOrFail($id);
        $cate->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.place_cates.index')->with('success', 'Category updated successfully.');
    }

    // Xóa địa điểm
    public function destroy($id)
    {
        $cate = Category::findOrFail($id);
        $cate->delete();

        return redirect()->route('admin.place_cates.index')->with('success', 'Category deleted successfully.');
    }
}
