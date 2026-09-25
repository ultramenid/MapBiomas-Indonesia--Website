<x-cms-layout title="Pages">
    <x-cms.page-header title="Pages" description="Editable content pages on the public site.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ url('/' . app()->getLocale() . '/about') }}" target="_blank">
                Preview About
            </x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <x-cms.data-table>
        <x-slot:head>
            <x-cms.th>Page</x-cms.th>
            <x-cms.th class="hidden md:table-cell">Last updated</x-cms.th>
            <x-cms.th class="w-24 text-right">Actions</x-cms.th>
        </x-slot:head>

        @foreach ($pages as $page)
            <tr class="transition-colors hover:bg-hover/50">
                <x-cms.td>
                    <p class="font-medium capitalize">{{ $page->name }}</p>
                    <p class="text-xs text-ink-muted">/{{ app()->getLocale() }}/{{ $page->name }}</p>
                </x-cms.td>
                <x-cms.td class="hidden text-ink-muted md:table-cell">
                    {{ $page->updated_at?->format('M j, Y') ?? '—' }}
                </x-cms.td>
                <x-cms.td class="text-right">
                    <x-cms.button variant="secondary" href="{{ route('cms.pages.edit', $page->name) }}">Edit</x-cms.button>
                </x-cms.td>
            </tr>
        @endforeach
    </x-cms.data-table>
</x-cms-layout>
