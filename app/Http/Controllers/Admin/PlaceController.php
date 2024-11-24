<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Category;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    // Hiển thị danh sách địa điểm
    public function index()
    {
        $places = Place::with('categories', 'images')->paginate(10);
        return view('admin.places.index', compact('places'));
    }

    // Hiển thị form tạo mới địa điểm
    public function create()
    {
        $categories = Category::all();
        return view('admin.places.create', compact('categories'));
    }

    // Lưu địa điểm mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $place = Place::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        $place->categories()->sync($request->categories);

        // Xử lý hình ảnh chính
        if ($request->hasFile('primary_image')) {
            $imagePath = $request->file('primary_image')->store('places', 'public');
            $place->images()->create(['path' => $imagePath, 'is_primary' => true]);
        }

        return redirect()->route('admin.places.index')->with('success', 'Place created successfully.');
    }

    // Hiển thị form chỉnh sửa địa điểm
    public function edit($id)
    {
        $place = Place::with('categories')->findOrFail($id);
        $categories = Category::all();
        return view('admin.places.edit', compact('place', 'categories'));
    }

    // Cập nhật địa điểm
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $place = Place::findOrFail($id);
        $place->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $place->categories()->sync($request->categories);

        // Xử lý hình ảnh chính nếu có
        if ($request->hasFile('primary_image')) {
            $imagePath = $request->file('primary_image')->store('places', 'public');
            $place->images()->update(['is_primary' => false]); // Xóa trạng thái "primary" cũ
            $place->images()->create(['path' => $imagePath, 'is_primary' => true]);
        }

        return redirect()->route('admin.places.index')->with('success', 'Place updated successfully.');
    }

    // Xóa địa điểm
    public function destroy($id)
    {
        $place = Place::findOrFail($id);
        $place->delete();

        return redirect()->route('admin.places.index')->with('success', 'Place deleted successfully.');
    }
}
