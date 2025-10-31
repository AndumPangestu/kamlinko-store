@props(['transaction'])

<div class="">
    <h1 class="text-xl font-semibold">Proof of Payment</h1>
    <div class="flex flex-row gap-5">
        @if ($transaction->getMedia('*')->count() > 0)
            @foreach ($transaction->getMedia('*') as $media)
                <div class="relative border border-gray-300" id="hoverDiv">
                    @php
                        $media->url = str_replace(
                            'https://admin.bangunanxpress.com',
                            'https://api.bangunanxpress.com',
                            $media->getUrl(),
                        );
                    @endphp
                    <img src="{{ $media->url }}" alt="Transaction Proof of Payment" class="w-48 h-48 object-scale-down">
                    <a href="{{ $media->url }}" target="_blank" rel="noopener noreferrer"
                        class="w-48 h-48 inline-block absolute top-0 bg-gray-100 opacity-0" id="hoverDiv">
                        <div class="flex flex-col h-full w-full items-center justify-center p-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-eye" viewBox="0 0 16 16">
                                <path
                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                <path
                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                            </svg>
                            <p class="text-center text-black">Open Image in New Tab</p>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-xl text-red-500">No proof of payment uploaded</p>
        @endif
    </div>
</div>

<style>
    #hoverDiv:hover a {
        opacity: 50%;
    }
</style>
