@extends('layouts.admin')

@section('title', 'Create Place')

@section('content')
<div class="container mt-4">
    <h1>Create Place</h1>
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
            <label for="primary_image">Primary Image</label>
            <input type="file" name="primary_image" id="primary_image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>
@endsection
