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
            <select name="categories[]" id="categories" class="form-control select2" multiple>
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

        <div class="form-group">
            <label>Videos</label>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal">
                Add Video
            </button>
        </div>

        <!-- Danh sách video -->
        <div id="video-list">
            @if(isset($place))
                @foreach($place->videos as $video)
                    <?php
                    $_tags = [];
                    foreach ($video->tags as $tag) {
                        $_tags[] = $tag->name;
                    }
                    ?>
                    <div class="video-item mb-2">
                        <input type="hidden" name="videos[{{ $loop->index }}][name]" value="{{ $video->name }}">
                        <input type="hidden" name="videos[{{ $loop->index }}][description]" value="{{ $video->description }}">
                        <input type="hidden" name="videos[{{ $loop->index }}][youtube_url]" value="{{ $video->youtube_url }}">
                        <input type="hidden" name="videos[{{ $loop->index }}][twitter_url]" value="{{ $video->twitter_url }}">
                        <input type="hidden" name="videos[{{ $loop->index }}][tags][]" value="{{ implode(',', $_tags) }}">
                        <input type="hidden" name="videos[{{ $loop->index }}][publisher]" value="{{ $video->publisher }}">

                        <p>
                            <strong>{{ $video->name }}</strong> ({{ $video->publisher }}) -
                            <a href="{{ $video->youtube_url }}" target="_blank">Youtube URL</a> -
                            <a href="{{ $video->twitter_url }}" target="_blank">Twitter URL</a>
                            <button type="button" class="btn btn-danger btn-sm remove-video-btn">Remove</button>
                        </p>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Nút cập nhật -->
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Add Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="video-form">
                    <div class="mb-3">
                        <label for="video-name" class="form-label">Name</label>
                        <input type="text" id="video-name" class="form-control" placeholder="Enter video name">
                    </div>
                    <div class="mb-3">
                        <label for="video-description" class="form-label">Description</label>
                        <textarea id="video-description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="video-youtube" class="form-label">Youtuber URL</label>
                        <input type="text" id="video-youtube" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="video-twitter" class="form-label">Twitter URL</label>
                        <input type="url" id="video-twitter" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="video-tags" class="form-label">Tags</label>
                        <select id="video-tags" class="form-control" multiple="multiple"></select>
                    </div>
                    <div class="mb-3">
                        <label for="video-publisher" class="form-label">Publisher</label>
                        <input type="text" id="video-publisher" class="form-control" placeholder="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="add-video-btn" class="btn btn-primary">Add Video</button>
            </div>
        </div>
    </div>
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

    $(document).ready(function () {
        let videoIndex = 0;

        $('#video-tags').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: 'Enter or select tags',
            ajax: {
                url: '', ////route("admin.tags.search")
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(tag => ({ id: tag.name, text: tag.name }))
                    };
                },
                cache: true
            }
        });

        // Xử lý thêm video
        $('#add-video-btn').on('click', function () {
            const name = $('#video-name').val();
            const description = $('#video-description').val();
            const youtube = $('#video-youtube').val();
            const twitter = $('#video-twitter').val();
            const tags = $('#video-tags').val(); // Lấy danh sách tags
            const publisher = $('#video-publisher').val();

            // if (!name || !type || !url) {
            //     alert('Please fill in all fields.');
            //     return;
            // }

            const videoItem = `
                <div class="video-item mb-2">
                    <input type="hidden" name="videos[${videoIndex}][name]" value="${name}">
                    <input type="hidden" name="videos[${videoIndex}][description]" value="${description}">
                    <input type="hidden" name="videos[${videoIndex}][youtube_url]" value="${youtube}">
                    <input type="hidden" name="videos[${videoIndex}][twitter_url]" value="${twitter}">
                    <input type="hidden" name="videos[${videoIndex}][tags][]" value="${tags.join(',')}">
                    <input type="hidden" name="videos[${videoIndex}][publisher]" value="${publisher}">
                    <p>
                        <strong>${name}</strong> (${publisher}) -
                        <a href="${youtube}" target="_blank">Youtube Url</a> -
                        <a href="${twitter}" target="_blank">Twitter Url</a>
                        <button type="button" class="btn btn-danger btn-sm remove-video-btn">Remove</button>
                    </p>
                </div>
            `;

            $('#video-list').append(videoItem);
            videoIndex++;

            $('#video-form')[0].reset();
            $('#video-tags').val(null).trigger('change'); // Reset tags
            $('#videoModal').modal('hide');
        });

        // Xử lý xóa video
        $('#video-list').on('click', '.remove-video-btn', function () {
            $(this).closest('.video-item').remove();
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
