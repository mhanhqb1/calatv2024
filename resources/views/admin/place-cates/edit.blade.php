@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container mt-4">
    <h1>Edit Category</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.place_cates.update', $cate->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tên địa điểm -->
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $cate->name) }}" required>
        </div>

        <!-- Nút cập nhật -->
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection

