@extends('layouts.admin')

@section('title', 'Manage Places')

@section('content')
<div class="container mt-4">
    <h1>Manage Places</h1>
    <a href="{{ route('admin.places.create') }}" class="btn btn-primary mb-3">Add New Place</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Categories</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($places as $place)
                <tr>
                    <td>{{ $place->id }}</td>
                    <td>
                        @if($place->images->where('is_primary', true)->first())
                            <img src="{{ asset('storage/' . $place->images->where('is_primary', true)->first()->url) }}" alt="Primary Image" width="50">
                        @else

                        @endif
                    </td>
                    <td>{{ $place->name }}</td>
                    <td>{{ $place->categories->pluck('name')->join(', ') }}</td>
                    <td>
                        <a href="{{ route('admin.places.edit', $place->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.places.destroy', $place->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No places found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $places->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
