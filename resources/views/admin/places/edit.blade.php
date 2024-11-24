@extends('layouts.admin')

@section('title', 'Edit Place')

@section('content')
<div class="container mt-4">
    <h1>Edit Place</h1>
    <form action="{{ route('admin.places.update', $place->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tên địa điểm -->
        <div class="form-group">
            <label for="name">Place Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $place->name) }}" required>
        </div>

        <!-- Mô tả -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $place->description) }}</textarea>
        </div>

        <!-- Danh mục -->
        <div class="form-group">
            <label for="categories">Categories</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ in_array($category->id, $place->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Hình ảnh chính -->
        <div class="form-group">
            <label for="primary_image">Primary Image</label>
            <input type="file" name="primary_image" id="primary_image" class="form-control">
            @if($place->images->where('is_primary', true)->first())
                <div class="mt-2">
                    <strong>Current Image:</strong><br>
                    <img src="{{ asset('storage/' . $place->images->where('is_primary', true)->first()->path) }}"
                         alt="Primary Image" width="100">
                </div>
            @endif
        </div>

        <!-- Nút cập nhật -->
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
