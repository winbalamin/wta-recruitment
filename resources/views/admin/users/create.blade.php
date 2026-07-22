<x-layouts.admin>
    <x-slot:title>Create admin account</x-slot:title>

    <nav class="mb-4 text-sm" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-brand-700 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to dashboard
        </a>
    </nav>

    <div class="max-w-xl">
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Create admin account</h1>
            <p class="mt-1 text-sm text-slate-500">Add a new administrator who can review applications and manage the console.</p>
        </div>

        <div class="card p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="label">Full name <span class="text-red-500" aria-hidden="true">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                           autocomplete="name"
                           class="input {{ $errors->has('name') ? 'input-invalid' : '' }}">
                    @error('name') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="label">Email <span class="text-red-500" aria-hidden="true">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="160"
                           autocomplete="email"
                           class="input {{ $errors->has('email') ? 'input-invalid' : '' }}">
                    @error('email') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label">Password <span class="text-red-500" aria-hidden="true">*</span></label>
                    <input id="password" type="password" name="password" required
                           autocomplete="new-password"
                           class="input {{ $errors->has('password') ? 'input-invalid' : '' }}">
                    @error('password') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Confirm password <span class="text-red-500" aria-hidden="true">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           autocomplete="new-password"
                           class="input">
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary cursor-pointer">Cancel</a>
                    <button type="submit" class="btn-primary cursor-pointer">Create admin</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
