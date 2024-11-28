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
            <select name="categories[]" id="categories" class="form-control select2" multiple>
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
                    <div class="video-item mb-2">
                        <input type="hidden" name="videos[{{ $loop->index }}][name]" value="{{ $video->name }}">
                        <input type="hidden" name="videos[{{ $loop->index }}][type]" value="{{ $video->type }}">
                        <input type="hidden" name="videos[{{ $loop->index }}][url]" value="{{ $video->url }}">

                        <p>
                            <strong>{{ $video->name }}</strong> ({{ $video->type }}) -
                            <a href="{{ $video->url }}" target="_blank">View</a>
                            <button type="button" class="btn btn-danger btn-sm remove-video-btn">Remove</button>
                        </p>
                    </div>
                @endforeach
            @endif
        </div>

        <button type="submit" class="btn btn-success">Create</button>
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
</script>

@endPush
