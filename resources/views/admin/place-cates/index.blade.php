@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="container mt-4">
    <h1>Manage Places</h1>
    <a href="{{ route('admin.place_cates.create') }}" class="btn btn-primary mb-3">Add New Category</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cates as $cate)
                <tr>
                    <td>{{ $cate->id }}</td>
                    <td>{{ $cate->name }}</td>
                    <td>
                        <a href="{{ route('admin.place_cates.edit', $cate->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.place_cates.destroy', $cate->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $cates->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
