@extends('layouts.admin')

@section('title', 'Edit Place')

@section('content')
<div class="container mt-4">
    <h1>Edit Place</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

        <div class="form-group">
            <label>Current Images</label>
            <div class="mt-2">
                @foreach($place->images as $image)
                    <div class="image-container" style="display: inline-block; position: relative; margin-right: 10px;">
                        <img src="{{ asset('storage/' . $image->url) }}" alt="Image" width="100">
                        <span class="btn btn-danger btn-sm delete-image-form" data-action="{{ route('admin.places.image.delete', $image->id) }}" style="background: none; border: none; color: red; cursor: pointer;">&times;</span>
                        <div>
                            <label>
                                <input type="radio" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }}>
                                Primary Image
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Hình ảnh chính -->
        <div class="form-group">
            <label for="images">Add New Images</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" onchange="previewImages()">
            <div id="preview-container" class="mt-3"></div>
        </div>

        <!-- Nút cập nhật -->
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.delete-image-form').on('click', function(e) {
            e.preventDefault(); // Ngăn chặn submit form mặc định
            const $this = $(this);
            const actionUrl = $this.attr('data-action');

            if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Xóa hình ảnh từ DOM
                        $this.closest('.image-container').remove();
                    },
                    error: function(xhr) {
                        alert('Có lỗi xảy ra!'); // Thông báo lỗi
                    }
                });
            }
        });
    });
    function previewImages() {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = ''; // Clear existing previews
        const files = document.getElementById('images').files;

        if (files) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.marginRight = '10px';
                    img.style.marginBottom = '10px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endPush
