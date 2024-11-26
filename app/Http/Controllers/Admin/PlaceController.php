<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstantCommon;
use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Category;
use App\Models\PlaceImage;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    // Hiển thị danh sách địa điểm
    public function index()
    {
        $places = Place::with('categories', 'images')->paginate(2);
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
            'images.*' => 'nullable|image|mimes:'.ConstantCommon::IMAGE_TYPES.'|max:'.ConstantCommon::IMAGE_LENGTH,
        ]);

        $place = Place::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        $place->categories()->sync($request->categories);

        // Xử lý hình ảnh chính
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $k => $image) {
                $imagePath = $image->store(ConstantCommon::PLACE_IMAGE_PATH, 'public');
                $isPrimary = empty($k) ? 1 : 0;
                $place->images()->create(['url' => $imagePath, 'is_primary' => $isPrimary]);
            }
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
            'images.*' => 'nullable|image|mimes:'.ConstantCommon::IMAGE_TYPES.'|max:'.ConstantCommon::IMAGE_LENGTH,
        ]);

        $place = Place::findOrFail($id);
        $place->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $place->categories()->sync($request->categories);

        if ($request->has('primary_image')) {
            $place->images()->update(['is_primary' => false]); // Đặt tất cả hình ảnh không phải chính
            $primaryImage = $place->images()->find($request->primary_image);
            if ($primaryImage) {
                $primaryImage->update(['is_primary' => true]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store(ConstantCommon::PLACE_IMAGE_PATH, 'public');
                $place->images()->create(['url' => $imagePath, 'is_primary' => false]);
            }
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

    public function deleteImage($id)
    {
        $image = PlaceImage::findOrFail($id);
        if (file_exists(storage_path('app/public/' . $image->url))) {
            unlink(storage_path('app/public/' . $image->url));
        }
        $image->delete();
        return back()->with('success', 'Image deleted successfully.');
    }
}
