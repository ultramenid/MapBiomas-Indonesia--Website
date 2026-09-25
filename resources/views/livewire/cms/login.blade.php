<div class="w-full max-w-sm">
    <div class="mb-6 flex flex-col items-center">
        <img src="{{ asset('assets/logo mapbiomas.png') }}" alt="MapBiomas Indonesia" class="h-12 w-auto">
    </div>

    <div class="rounded-lg border border-line bg-surface p-6 shadow-sm">
        <h1 class="text-base font-semibold text-ink">Log in to your account</h1>
        <p class="mt-1 text-sm text-ink-muted">Enter your credentials to access the CMS.</p>

        <form wire:submit="login" class="mt-5 space-y-4">
            <x-cms.input name="email" type="email" label="Email" placeholder="name@example.com"
                         wire:model="email" autofocus autocomplete="email" />

            <x-cms.input name="password" type="password" label="Password"
                         wire:model="password" autocomplete="current-password" />

            <label class="flex items-center gap-2 text-sm text-ink-muted">
                <input type="checkbox" wire:model="remember"
                       class="h-3.5 w-3.5 rounded border-line-strong text-accent focus:ring-accent/40">
                Remember me
            </label>

            <x-cms.button type="submit" loadingTarget="login" class="w-full">Log in</x-cms.button>
        </form>
    </div>
</div>
