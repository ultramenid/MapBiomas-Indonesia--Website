<div class="border-t-2 border-[#86B696] py-6 w-full h-full mt-12 bg-gray-200 px-4">
    <div class="flex sm:flex-row flex-col items-center sm:gap-10 lg:gap-32 gap-4 max-w-6xl mx-auto">
        <div class="w-full sm:flex-1 min-w-0">
            <h1>Co-creators</h1>
            <div class="flex justify-between w-full flex-wrap gap-4">
                @forelse ($coCreators as $partner)
                    <a href="{{ $partner->url ?? '#' }}" target="_blank" rel="noopener" title="{{ $partner->name }}">
                        <img src="{{ media_url($partner->logo_path) }}" alt="{{ $partner->name }}"
                             class="{{ $loop->first ? 'h-10 mt-2' : 'h-12' }}">
                    </a>
                @empty
                    <p class="text-sm">—</p>
                @endforelse
            </div>
        </div>

        <div class="sm:w-2/12 w-full">
            <h1 class="">Supported by</h1>
            <div class="flex flex-wrap gap-4">
                @forelse ($supporters as $partner)
                    <a href="{{ $partner->url ?? '#' }}" target="_blank" rel="noopener" title="{{ $partner->name }}">
                        <img src="{{ media_url($partner->logo_path) }}" alt="{{ $partner->name }}" class="h-12">
                    </a>
                @empty
                    <p class="text-sm">—</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
