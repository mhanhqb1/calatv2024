@extends('layouts.admin')

@section('title', 'Create Place')

@section('content')
<div class="container mt-4">

    <h1>Create Place</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.places.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Place Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="categories">Categories</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="images">Upload Images</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" onchange="previewImages()">
            <div id="preview-container" class="mt-3"></div>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
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
