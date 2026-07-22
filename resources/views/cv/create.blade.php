<x-layouts.app-public>
    <x-slot:title>Apply to join WTA</x-slot:title>

    @push('styles')
        <style>
            .step-active   { @apply border-brand-700 text-brand-700; }
            .step-inactive { @apply border-slate-200 text-slate-400; }
        </style>
    @endpush

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-700 to-brand-900 text-white">
        <div class="absolute inset-0 opacity-10" aria-hidden="true">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>
        <div class="container relative py-12 sm:py-16 text-center">
            <div class="inline-flex items-center justify-center gap-2 h-20 sm:h-24 rounded-2xl bg-white mb-6 ring-1 ring-white/30 shadow-lg p-3">
                <img src="{{ asset('images/WinThinLogo.png') }}" alt="WinThin Logo" class="h-14 w-auto sm:h-16">
                <img src="{{ asset('images/WCL_logo.png') }}" alt="WCL Logo" class="h-14 w-auto sm:h-16">
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-slate-900" style="color: white; font-weight: bold;"~~>Join the WTA Team</h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto" style="color: white; font-weight: bold;">
                Submit your CV and we'll get back to you about an interview. The form takes about 3 minutes.
            </p>

            {{-- Stepper --}}
            <ol class="mt-8 flex items-center justify-center gap-2 sm:gap-4 max-w-2xl mx-auto" aria-label="Form progress">
                @php
                    $steps = [
                        ['label' => 'Personal', 'active' => true],
                        ['label' => 'Professional', 'active' => false],
                        ['label' => 'Documents', 'active' => false],
                        ['label' => 'Background', 'active' => false],
                    ];
                @endphp
                @foreach($steps as $i => $step)
                    <li class="flex items-center gap-2 sm:gap-3 {{ $loop->last ? '' : 'flex-1' }}">
                        <span class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full text-xs font-semibold border-2 {{ $step['active'] ? 'bg-white text-brand-700 border-white' : 'bg-transparent text-white border-white/40' }}"
                              aria-current="{{ $step['active'] ? 'step' : 'false' }}">
                            {{ $i + 1 }}
                        </span>
                        <span class="hidden sm:inline text-sm font-medium {{ $step['active'] ? 'text-white' : 'text-brand-200' }}">{{ $step['label'] }}</span>
                        @unless($loop->last)
                            <span class="flex-1 h-px bg-white/30" aria-hidden="true"></span>
                        @endunless
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Form --}}
    <section class="container -mt-8 sm:-mt-12 pb-16 relative z-10">
        <div class="max-w-3xl mx-auto">

            @if ($errors->any())
                <div role="alert" aria-live="assertive" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 sm:p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.94 6.94a1.5 1.5 0 112.12 2.12L10 10.12l-1.06 1.06a1.5 1.5 0 11-2.12-2.12L7.88 10 6.82 8.94a1.5 1.5 0 112.12-2.12L10 7.88l1.06-1.06a1.5 1.5 0 112.12 2.12L10.12 10l1.06 1.06a1.5 1.5 0 11-2.12 2.12L10 12.12l-1.06 1.06a1.5 1.5 0 11-2.12-2.12L7.88 10 6.82 8.94z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('cv.store') }}" enctype="multipart/form-data"
                  class="card p-6 sm:p-8 space-y-8"
                  id="cv-form"
                  onsubmit="var b=this.querySelector('button[type=submit]'); b.disabled=true; b.querySelector('.btn-label').classList.add('hidden'); b.querySelector('.btn-loader').classList.remove('hidden');">

                @csrf

                {{-- Section: Personal --}}
                <fieldset>
                    <legend class="font-display text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-100 text-brand-700 text-sm font-semibold">1</span>
                        Personal information
                    </legend>
                    <p class="mt-1 text-sm text-slate-500">Tell us who you are.</p>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="label">Full name <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                                   autocomplete="name"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('name') ? 'input-invalid' : '' }}">
                            @error('name') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="position_applied" class="label">Position applied for <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="position_applied" type="text" name="position_applied" value="{{ old('position_applied') }}" required maxlength="120"
                                   placeholder="e.g. Sales Executive, Web Developer"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('position_applied') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('position_applied') ? 'input-invalid' : '' }}">
                            @error('position_applied') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="label">Date of birth <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('date_of_birth') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('date_of_birth') ? 'input-invalid' : '' }}">
                            @error('date_of_birth') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="education_level" class="label">Highest education level <span class="text-red-500" aria-hidden="true">*</span></label>
                            <select id="education_level" name="education_level" required
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('education_level') ? 'true' : 'false' }}"
                                    class="input {{ $errors->has('education_level') ? 'input-invalid' : '' }}">
                                <option value="" disabled {{ old('education_level') ? '' : 'selected' }}>Select level</option>
                                <option value="High School" {{ old('education_level') == 'High School' ? 'selected' : '' }}>High School</option>
                                <option value="Diploma" {{ old('education_level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Bachelor" {{ old('education_level') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                <option value="Master" {{ old('education_level') == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                <option value="Other" {{ old('education_level') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('education_level') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                    <div class="sm:col-span-2">
                        <label class="label">NRC number <span class="text-red-500" aria-hidden="true">*</span></label>
                        <div class="flex flex-row gap-2">
                            <select id="nrc_state" name="nrc_state" required
                                    aria-label="NRC state number"
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('nrc') ? 'true' : 'false' }}"
                                    class="w-20 input {{ $errors->has('nrc') ? 'input-invalid' : '' }}">
                                <option value="" disabled {{ old('nrc_state') ? '' : 'selected' }}>State</option>
                                @foreach($nrcStates as $state)
                                    <option value="{{ $state }}" {{ old('nrc_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>

                            <select id="nrc_township" name="nrc_township" required
                                    aria-label="NRC township code"
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('nrc') ? 'true' : 'false' }}"
                                    class="w-25 input {{ $errors->has('nrc') ? 'input-invalid' : '' }}">
                                <option value="" disabled {{ old('nrc_township') ? '' : 'selected' }}>Township</option>
                            </select>

                            <select id="nrc_type" name="nrc_type" required
                                    aria-label="NRC type"
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('nrc') ? 'true' : 'false' }}"
                                    class="w-20 input {{ $errors->has('nrc') ? 'input-invalid' : '' }}">
                                <option value="" disabled {{ old('nrc_type') ? '' : 'selected' }}>Type</option>
                                <option value="N" {{ old('nrc_type') == 'N' ? 'selected' : '' }}>(N)</option>
                                <option value="P" {{ old('nrc_type') == 'P' ? 'selected' : '' }}>(P)</option>
                                <option value="E" {{ old('nrc_type') == 'E' ? 'selected' : '' }}>(E)</option>
                            </select>

                            <input id="nrc_number" type="text" name="nrc_number" value="{{ old('nrc_number') }}"
                                   inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required
                                   aria-label="NRC registration number"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('nrc') ? 'true' : 'false' }}"
                                   placeholder="123456"
                                   class="w-28 input {{ $errors->has('nrc') ? 'input-invalid' : '' }}">
                        </div>
                        @error('nrc') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                    </div>
                        <div class="sm:col-span-2">
                            <label for="address" class="label">Address <span class="text-red-500" aria-hidden="true">*</span></label>
                            <textarea id="address" name="address" rows="2" required maxlength="1000"
                                      aria-required="true"
                                      aria-invalid="{{ $errors->has('address') ? 'true' : 'false' }}"
                                      class="input {{ $errors->has('address') ? 'input-invalid' : '' }}">{{ old('address') }}</textarea>
                            @error('address') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="label">Email <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="160"
                                   autocomplete="email"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('email') ? 'input-invalid' : '' }}">
                            @error('email') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="label">Phone <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required maxlength="40"
                                   autocomplete="tel"
                                   placeholder="+95 9 123 456 789"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('phone') ? 'input-invalid' : '' }}">
                            @error('phone') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_name" class="label">Emergency contact name <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required maxlength="120"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('emergency_contact_name') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('emergency_contact_name') ? 'input-invalid' : '' }}">
                            @error('emergency_contact_name') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_relationship" class="label">Relationship <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="emergency_contact_relationship" type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" required maxlength="40"
                                   placeholder="e.g. Parent, Spouse"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('emergency_contact_relationship') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('emergency_contact_relationship') ? 'input-invalid' : '' }}">
                            @error('emergency_contact_relationship') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_phone" class="label">Emergency contact phone <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input id="emergency_contact_phone" type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required maxlength="40"
                                   placeholder="+95 9 123 456 789"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('emergency_contact_phone') ? 'true' : 'false' }}"
                                   class="input {{ $errors->has('emergency_contact_phone') ? 'input-invalid' : '' }}">
                            @error('emergency_contact_phone') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Section: Professional --}}
                <fieldset>
                    <legend class="font-display text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-100 text-brand-700 text-sm font-semibold">2</span>
                        Professional
                    </legend>
                    <p class="mt-1 text-sm text-slate-500">Tell us about your current role and expectations.</p>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="current_employer" class="label">Current employer</label>
                            <input id="current_employer" type="text" name="current_employer" value="{{ old('current_employer') }}" maxlength="120"
                                   class="input {{ $errors->has('current_employer') ? 'input-invalid' : '' }}">
                            @error('current_employer') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="current_job_title" class="label">Current job title</label>
                            <input id="current_job_title" type="text" name="current_job_title" value="{{ old('current_job_title') }}" maxlength="120"
                                   class="input {{ $errors->has('current_job_title') ? 'input-invalid' : '' }}">
                            @error('current_job_title') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="expected_salary" class="label">Expected salary (MMK)</label>
                            <input id="expected_salary" type="number" name="expected_salary" value="{{ old('expected_salary') }}" min="0" step="0.01"
                                   placeholder="e.g. 500000"
                                   class="input {{ $errors->has('expected_salary') ? 'input-invalid' : '' }}">
                            @error('expected_salary') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="start_date" class="label">Earliest start date</label>
                            <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}"
                                   class="input {{ $errors->has('start_date') ? 'input-invalid' : '' }}">
                            @error('start_date') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="languages" class="label">Languages spoken</label>
                            <input id="languages" type="text" name="languages" value="{{ old('languages') }}" maxlength="255"
                                   placeholder="e.g. Myanmar, English, Chinese"
                                   class="input {{ $errors->has('languages') ? 'input-invalid' : '' }}">
                            @error('languages') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="portfolio_url" class="label">Portfolio / LinkedIn URL</label>
                            <input id="portfolio_url" type="url" name="portfolio_url" value="{{ old('portfolio_url') }}" maxlength="255"
                                   placeholder="https://linkedin.com/in/yourprofile"
                                   class="input {{ $errors->has('portfolio_url') ? 'input-invalid' : '' }}">
                            @error('portfolio_url') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="skills" class="label">Skills & certifications</label>
                            <textarea id="skills" name="skills" rows="3" maxlength="2000"
                                      placeholder="List relevant skills, tools, licenses or certificates."
                                      class="input {{ $errors->has('skills') ? 'input-invalid' : '' }}">{{ old('skills') }}</textarea>
                            <p class="help-text">Optional &middot; up to 2,000 characters</p>
                            @error('skills') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Section: Documents --}}
                <fieldset>
                    <legend class="font-display text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-100 text-brand-700 text-sm font-semibold">3</span>
                        Documents
                    </legend>
                    <p class="mt-1 text-sm text-slate-500">Upload your photo and NRC scan (both optional but recommended).</p>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="photo" class="label">Photo</label>
                            <input id="photo" type="file" name="photo" accept="image/jpeg,image/png"
                                   aria-describedby="photo-help"
                                   class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold hover:file:bg-brand-100 transition-colors duration-200">
                            <p id="photo-help" class="help-text">JPG, JPEG or PNG &middot; max 2 MB</p>
                            @error('photo') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="nrc_file" class="label">NRC attachment</label>
                            <input id="nrc_file" type="file" name="nrc_file" accept="image/jpeg,image/png,application/pdf"
                                   aria-describedby="nrc-help"
                                   class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold hover:file:bg-brand-100 transition-colors duration-200">
                            <p id="nrc-help" class="help-text">JPG, PNG or PDF &middot; max 100 MB</p>
                            @error('nrc_file') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Section: Background --}}
                <fieldset>
                    <legend class="font-display text-lg font-semibold text-slate-900 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-100 text-brand-700 text-sm font-semibold">4</span>
                        Background
                    </legend>
                    <p class="mt-1 text-sm text-slate-500">Help us understand your experience and motivation.</p>

                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="work_experience" class="label">Work experience</label>
                            <textarea id="work_experience" name="work_experience" rows="5" maxlength="5000"
                                      placeholder="List your previous roles, companies, and dates."
                                      class="input">{{ old('work_experience') }}</textarea>
                            <p class="help-text">Optional &middot; up to 5,000 characters</p>
                            @error('work_experience') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="education" class="label">Education</label>
                            <textarea id="education" name="education" rows="4" maxlength="5000"
                                      placeholder="List your schools, degrees, and graduation years."
                                      class="input">{{ old('education') }}</textarea>
                            <p class="help-text">Optional &middot; up to 5,000 characters</p>
                            @error('education') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="references" class="label">References</label>
                            <textarea id="references" name="references" rows="4" maxlength="3000"
                                      placeholder="Name, relationship, and contact details of one or more professional references."
                                      class="input {{ $errors->has('references') ? 'input-invalid' : '' }}">{{ old('references') }}</textarea>
                            <p class="help-text">Optional &middot; up to 3,000 characters</p>
                            @error('references') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="why_join_wta" class="label">Why do you want to join WTA? <span class="text-red-500" aria-hidden="true">*</span></label>
                            <textarea id="why_join_wta" name="why_join_wta" rows="5" required minlength="20" maxlength="5000"
                                      placeholder="Tell us why you are interested in joining WTA (minimum 20 characters)."
                                      aria-required="true"
                                      aria-invalid="{{ $errors->has('why_join_wta') ? 'true' : 'false' }}"
                                      class="input {{ $errors->has('why_join_wta') ? 'input-invalid' : '' }}">{{ old('why_join_wta') }}</textarea>
                            <p class="help-text">Required &middot; minimum 20 characters</p>
                            @error('why_join_wta') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Actions --}}
                <div class="pt-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-xs text-slate-500">By submitting, you confirm that the information provided is accurate.</p>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="btn-secondary cursor-pointer">Clear</button>
                        <button type="submit"
                                class="btn-primary min-w-[10rem] cursor-pointer disabled:opacity-70">
                            <span class="btn-label inline-flex items-center gap-2">Submit application</span>
                            <span class="btn-loader hidden inline-flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script>
            const nrcTownships = @json($nrcTownships);

            const stateSelect = document.getElementById('nrc_state');
            const townshipSelect = document.getElementById('nrc_township');
            const selectedTownship = @json(old('nrc_township'));

            function populateTownships(state) {
                townshipSelect.innerHTML = '<option value="" disabled selected>Township</option>';

                if (! state || ! nrcTownships[state]) {
                    return;
                }

                nrcTownships[state].forEach(function (township) {
                    const option = document.createElement('option');
                    option.value = township;
                    option.textContent = township;

                    if (selectedTownship === township) {
                        option.selected = true;
                    }

                    townshipSelect.appendChild(option);
                });
            }

            stateSelect.addEventListener('change', function () {
                populateTownships(this.value);
            });

            if (stateSelect.value) {
                populateTownships(stateSelect.value);
            }
        </script>
    @endpush
</x-layouts.app-public>
