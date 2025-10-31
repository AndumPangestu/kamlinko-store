<x-app-layout>
    <x-slot name="header">
        <!-- Breadcrumbs -->
        <nav class="text-gray-500 text-sm mb-2" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center">
                <li class="">
                    <a href="{{ route('welcome') }}" class="text-gray-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </a>
                </li>
                <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ route('admin.banner-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Banner Management
                    </a>
                </li>

                <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ isset($banner) ? route('admin.banner-management.view-banner-details', $banner->id) : route('admin.banner-management.create') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        {{ isset($banner) ? 'Edit Banner' : 'Add New Banner' }}
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ isset($banner) ? __('Edit Banner') : __('Add Banner') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ isset($banner) ? __('Edit the banner details here') : __('Add a new banner here') }}
    </x-slot>
    <div class="py-12">
        <!-- Message -->
        @if (session('message'))
            <div class="text-green-500 text-center">
                {{ session('message') }}
            </div>
        @endif
        @if (session('error'))
            <div class="text-red-500 text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Banner details in general -->
        <section class="rounded-lg border border-gray-300 p-5">
            <h2 class="text-2xl font-semibold">Banner Details</h2>
            <form class="pt-5 flex flex-col gap-2" method="POST"
                action="{{ isset($banner) ? route('admin.banner-management.update', $banner->id) : route('admin.banner-management.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($banner))
                    @method('PUT')
                @endif

                <span>
                    <label for="name" class="text-lg font-semibold">Name</label>
                    <input type="text" name="name" id="banner-name"
                        value="{{ old('name', isset($banner) ? $banner->name : '') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter banner name">
                </span>

                <span>
                    <label for="name" class="text-lg font-semibold">Description</label>
                    <textarea name="description" id="banner-description" class="w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Enter banner description">{{ old('description', isset($banner) ? $banner->description : '') }}</textarea>
                </span>

                <!-- Media Type Selection -->
                <span>
                    <label class="text-lg font-semibold">Media Type</label>
                    <div>
                        <input type="radio" name="type" value="image" id="media_type_image"
                            {{ old('type', isset($banner) && $banner->type ? 'image' : '') == 'image' ? 'checked' : '' }}>
                        <label for="media_type_image">Image</label>
                    </div>
                    <div>
                        <input type="radio" name="type" value="youtube" id="media_type_youtube"
                            {{ old('type', isset($banner) && $banner->type ? 'youtube' : '') == 'youtube' ? 'checked' : '' }}>
                        <label for="media_type_youtube">YouTube Video</label>
                    </div>
                </span>

                <!-- Image Upload -->
                <span id="image_upload">
                    <label for="image" class="text-lg font-semibold">Upload Image</label>
                    <input type="file" name="image" id="banner-image"
                        class="w-full border border-gray-300 rounded-lg p-2">
                </span>

                <!-- YouTube URL -->
                <span id="youtube_url">
                    <label for="youtube_url" class="text-lg font-semibold">YouTube URL</label>
                    <input type="url" name="youtube_url" id="banner-youtube-url"
                        value="{{ old('youtube_url', isset($banner) ? $banner->youtube_url : '') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter YouTube URL">
                </span>

                <!-- Media Preview -->
                <div class="pb-5">
                    <span id="media_preview" class="pb-5 w-[50rem]">
                        <label class="text-lg font-semibold">Media Preview</label>
                        <div id="preview_container"></div>
                    </span>
                </div>
                @if (isset($banner))
                    @foreach ($banner->getMedia('banners') as $media)
                        <input type="hidden" id="existing_image" value="{{ old('image', $media->getUrl()) }}">
                    @endforeach
                    @foreach ($banner->getMedia('banners/yt') as $media)
                        <input type="hidden" id="existing_youtube_url"
                            value="{{ old('youtube_url', $media->getCustomProperty('url')) }}">
                    @endforeach
                @endif

                @if (isset($banner))
                    <div class="flex flex-row gap-10">
                        <span class="text-sm text-gray-500">
                            <p>Created at:</p>
                            <p>{{ $banner->created_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($banner->created_by) ? route('admin.user-management.view-user-details', $banner->admin_create->id) : '#' }}">
                                    {{ isset($banner->created_by) ? $banner->admin_create->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                        <span class="text-sm text-gray-500">
                            <p>Updated at:</p>
                            <p>{{ $banner->updated_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($banner->updated_by) ? route('admin.user-management.view-user-details', $banner->admin_update->id) : '#' }}">
                                    {{ isset($banner->updated_by) ? $banner->admin_update->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                    </div>
                @endif

                <span class="pt-5">
                    <button type="submit"
                        class="bg-[#1737A4] text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">
                        Save Banner
                    </button>
                </span>
            </form>
        </section>
    </div>

    <!-- JavaScript for toggling input fields and preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageUpload = document.getElementById('image_upload');
            const youtubeURL = document.getElementById('youtube_url');
            const mediaTypeImage = document.getElementById('media_type_image');
            const mediaTypeYoutube = document.getElementById('media_type_youtube');
            const mediaPreview = document.getElementById('media_preview');
            const previewContainer = document.getElementById('preview_container');

            // Get existing data from hidden inputs
            const existingImage = document.getElementById('existing_image')?.value || null;
            const existingYoutubeURL = document.getElementById('existing_youtube_url')?.value || null;
            const youtubeURLInput = document.getElementById('banner-youtube-url');

            function loadExistingData() {
                if (existingImage) {
                    mediaTypeImage.checked = true;
                    imageUpload.style.display = 'block';
                    youtubeURL.style.display = 'none';
                    previewContainer.innerHTML = '<img src="' + existingImage +
                        '" class="" />';
                    mediaPreview.style.display = 'inherit';
                } else if (existingYoutubeURL) {
                    mediaTypeYoutube.checked = true;
                    imageUpload.style.display = 'none';
                    youtubeURL.style.display = 'block';
                    document.getElementById('banner-youtube-url').value = existingYoutubeURL;
                    const videoId = new URL(existingYoutubeURL).searchParams.get("v");
                    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                    previewContainer.innerHTML = '<iframe width="560" height="315" src="' + embedUrl +
                        '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                    mediaPreview.style.display = 'block';
                } else {
                    // mediaTypeImage.checked = true;
                    imageUpload.style.display = 'block';
                    youtubeURL.style.display = 'none';
                    mediaPreview.style.display = 'none';
                }
            }

            // Load existing data on page load
            loadExistingData();

            // Toggle fields and reset preview on media type change
            mediaTypeImage.addEventListener('change', function() {
                if (mediaTypeImage.checked) {
                    imageUpload.style.display = 'block';
                    youtubeURL.style.display = 'none';
                    mediaPreview.style.display = 'none';
                }
            });

            mediaTypeYoutube.addEventListener('change', function() {
                if (mediaTypeYoutube.checked) {
                    imageUpload.style.display = 'none';
                    youtubeURL.style.display = 'block';
                    mediaPreview.style.display = 'none';
                }
            });

            // Update preview when image is selected
            document.getElementById('banner-image').addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = '<img src="' + e.target.result +
                            '" class="w-full rounded-lg pb-5 object-cover" />';
                        mediaPreview.style.display = 'inherit';
                    };
                    reader.readAsDataURL(file);
                } else {
                    mediaPreview.style.display = 'none';
                }
            });

            // Update preview when YouTube URL changes
            document.getElementById('banner-youtube-url').addEventListener('input', function() {
                const url = this.value;
                if (url) {
                    const videoId = new URL(url).searchParams.get("v");
                    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                    previewContainer.innerHTML = '<iframe width="560" height="315" src="' + embedUrl +
                        '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                    mediaPreview.style.display = 'block';
                } else {
                    mediaPreview.style.display = 'none';
                }
            });

            // retain old youtube url if exception occurs
            function updateYouTubeEmbed(url) {
                if (url) {
                    const videoId = new URL(url).searchParams.get("v");
                    if (videoId) {
                        const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                        document.getElementById('preview_container').innerHTML =
                            '<iframe width="560" height="315" src="' + embedUrl +
                            '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                        document.getElementById('media_preview').style.display = 'block';
                    }
                } else {
                    document.getElementById('media_preview').style.display = 'none';
                }
            }

            // Update YouTube embed on input
            youtubeURLInput.addEventListener('input', function() {
                updateYouTubeEmbed(this.value);
            });

            // Initial embed update on page load
            if (youtubeURLInput.value) {
                updateYouTubeEmbed(youtubeURLInput.value);
            }
        });
    </script>

</x-app-layout>
