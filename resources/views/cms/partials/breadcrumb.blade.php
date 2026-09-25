@php
    $routeName = request()->route()?->getName() ?? '';

    $sections = [
        'cms.pages'       => ['label' => 'Pages',       'singular' => 'Page',       'index' => 'cms.pages.index'],
        'cms.news'        => ['label' => 'News',        'singular' => 'news',       'index' => 'cms.news.index'],
        'cms.faq'         => ['label' => 'FAQ',         'singular' => 'FAQ',        'index' => 'cms.faq.index'],
        'cms.team'        => ['label' => 'Team',        'singular' => 'member',     'index' => 'cms.team.index'],
        'cms.initiatives' => ['label' => 'Initiatives', 'singular' => 'initiative', 'index' => 'cms.initiatives.index'],
        'cms.partners'    => ['label' => 'Partners',    'singular' => 'partner',    'index' => 'cms.partners.index'],
        'cms.users'       => ['label' => 'Users',       'singular' => 'user',       'index' => 'cms.users.index'],
    ];

    $crumbs = [];

    if ($routeName === 'cms.dashboard') {
        $crumbs[] = ['label' => 'Dashboard'];
    } elseif (str_starts_with($routeName, 'cms.')) {
        $crumbs[] = ['label' => 'Dashboard', 'route' => 'cms.dashboard'];

        foreach ($sections as $prefix => $section) {
            if (! str_starts_with($routeName, $prefix . '.')) {
                continue;
            }

            $suffix = substr($routeName, strlen($prefix) + 1);

            if ($suffix === 'index') {
                $crumbs[] = ['label' => $section['label']];
            } elseif ($suffix === 'create') {
                $crumbs[] = ['label' => $section['label'], 'route' => $section['index']];
                $crumbs[] = ['label' => 'New ' . $section['singular']];
            } elseif ($suffix === 'edit') {
                $crumbs[] = ['label' => $section['label'], 'route' => $section['index']];
                $crumbs[] = ['label' => $prefix === 'cms.pages'
                    ? ucfirst(request()->route('slug') ?? '')
                    : 'Edit ' . $section['singular']];
            }

            break;
        }
    }
@endphp

@if ($crumbs)
    <nav aria-label="Breadcrumb" class="flex min-w-0 flex-1 items-center gap-1 overflow-hidden pl-3 text-sm lg:pl-0">
        @foreach ($crumbs as $i => $crumb)
            @if ($i > 0)
                <svg class="h-3.5 w-3.5 shrink-0 text-ink-subtle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            @endif
            @isset($crumb['route'])
                <a href="{{ route($crumb['route']) }}"
                   class="whitespace-nowrap text-ink-muted transition-colors hover:text-ink">{{ $crumb['label'] }}</a>
            @else
                <span class="truncate font-medium text-ink" aria-current="page">{{ $crumb['label'] }}</span>
            @endisset
        @endforeach
    </nav>
@endif