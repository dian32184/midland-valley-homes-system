@php
    $employmentChoices = ['employed', 'self_employed', 'ofw', 'unemployed', 'contractual', 'retired'];
@endphp

<div class="flex flex-col gap-1">
    <label for="first_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">First name</label>
    <input id="first_name" name="first_name" value="{{ old('first_name', $customer?->first_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900" required>
</div>
<div class="flex flex-col gap-1">
    <label for="last_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Last name</label>
    <input id="last_name" name="last_name" value="{{ old('last_name', $customer?->last_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900" required>
</div>
<div class="flex flex-col gap-1">
    <label for="middle_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Middle name</label>
    <input id="middle_name" name="middle_name" value="{{ old('middle_name', $customer?->middle_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="email" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email', $customer?->email) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="phone" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Phone</label>
    <input id="phone" name="phone" value="{{ old('phone', $customer?->phone) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<p class="md:col-span-2 text-sm font-bold text-gray-800 dark:text-gray-200 pt-2 border-t border-gray-200 dark:border-gray-600">Present address</p>
<div class="md:col-span-2 flex flex-col gap-1">
    <label for="present_address_street" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Street / house no.</label>
    <input id="present_address_street" name="present_address_street" value="{{ old('present_address_street', $customer?->present_address_street ?: $customer?->address) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="present_address_barangay" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Barangay</label>
    <input id="present_address_barangay" name="present_address_barangay" value="{{ old('present_address_barangay', $customer?->present_address_barangay) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="present_address_city" class="text-xs font-semibold text-gray-600 dark:text-gray-400">City / municipality</label>
    <input id="present_address_city" name="present_address_city" value="{{ old('present_address_city', $customer?->present_address_city) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="present_address_province" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Province</label>
    <input id="present_address_province" name="present_address_province" value="{{ old('present_address_province', $customer?->present_address_province) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="present_address_zip" class="text-xs font-semibold text-gray-600 dark:text-gray-400">ZIP code</label>
    <input id="present_address_zip" name="present_address_zip" value="{{ old('present_address_zip', $customer?->present_address_zip) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<p class="md:col-span-2 text-sm font-bold text-gray-800 dark:text-gray-200 pt-2 border-t border-gray-200 dark:border-gray-600">Spouse</p>
<div class="flex flex-col gap-1">
    <label for="spouse_first_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Spouse first name</label>
    <input id="spouse_first_name" name="spouse_first_name" value="{{ old('spouse_first_name', $customer?->spouse_first_name ?: $customer?->spouse_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="spouse_middle_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Spouse middle name</label>
    <input id="spouse_middle_name" name="spouse_middle_name" value="{{ old('spouse_middle_name', $customer?->spouse_middle_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="spouse_last_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Spouse last name</label>
    <input id="spouse_last_name" name="spouse_last_name" value="{{ old('spouse_last_name', $customer?->spouse_last_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="spouse_employment" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Spouse employment type</label>
    <select id="spouse_employment" name="spouse_employment" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
        <option value="">Select type</option>
        @foreach ($employmentChoices as $employment)
            <option value="{{ $employment }}" @selected(old('spouse_employment', $customer?->spouse_employment) === $employment)>{{ ucfirst(str_replace('_', ' ', $employment)) }}</option>
        @endforeach
    </select>
</div>
<div class="flex flex-col gap-1">
    <label for="spouse_monthly_income" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Spouse monthly income (PHP)</label>
    <input id="spouse_monthly_income" name="spouse_monthly_income" type="number" step="0.01" value="{{ old('spouse_monthly_income', $customer?->spouse_monthly_income) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<p class="md:col-span-2 text-sm font-bold text-gray-800 dark:text-gray-200 pt-2 border-t border-gray-200 dark:border-gray-600">Employment</p>
<div class="flex flex-col gap-1 md:col-span-2">
    <label for="employment_status" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Your employment type</label>
    <select id="employment_status" name="employment_status" class="rounded border-gray-300 js-simple-employment-status dark:border-gray-600 dark:bg-gray-900" required>
        @foreach ($employmentChoices as $employment)
            <option value="{{ $employment }}" @selected(old('employment_status', $customer?->employment_status ?? 'employed') === $employment)>{{ ucfirst(str_replace('_', ' ', $employment)) }}</option>
        @endforeach
    </select>
</div>

<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="employed contractual">
    <label for="employer_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Employer name</label>
    <input id="employer_name" name="employer_name" value="{{ old('employer_name', $customer?->employer_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="employed contractual">
    <label for="job_title" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Job title</label>
    <input id="job_title" name="job_title" value="{{ old('job_title', $customer?->job_title) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="employed contractual">
    <label for="years_employed" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Years employed</label>
    <input id="years_employed" name="years_employed" type="number" min="0" value="{{ old('years_employed', $customer?->years_employed) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="self_employed">
    <label for="business_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Business name</label>
    <input id="business_name" name="business_name" value="{{ old('business_name', $customer?->business_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="self_employed">
    <label for="business_nature" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Nature of business</label>
    <input id="business_nature" name="business_nature" value="{{ old('business_nature', $customer?->business_nature) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="self_employed">
    <label for="years_in_business" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Years in business</label>
    <input id="years_in_business" name="years_in_business" type="number" min="0" value="{{ old('years_in_business', $customer?->years_in_business) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="ofw">
    <label for="ofw_employer_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Employer abroad</label>
    <input id="ofw_employer_name" name="ofw_employer_name" value="{{ old('ofw_employer_name', $customer?->ofw_employer_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1 employment-simple-panel hidden" data-show-when="ofw">
    <label for="ofw_country" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Country of work</label>
    <input id="ofw_country" name="ofw_country" value="{{ old('ofw_country', $customer?->ofw_country) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<div class="flex flex-col gap-1 md:col-span-2">
    <label for="monthly_income" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Monthly income (PHP)</label>
    <input id="monthly_income" name="monthly_income" type="number" step="0.01" value="{{ old('monthly_income', $customer?->monthly_income) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<div class="flex flex-col gap-1">
    <label for="status" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Status</label>
    <select id="status" name="status" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900" required>
        @foreach (['pending', 'for_approval', 'approved', 'rejected'] as $status)
            <option value="{{ $status }}" @selected(old('status', $customer?->status ?? 'pending') === $status)>{{ str_replace('_', ' ', $status) }}</option>
        @endforeach
    </select>
</div>
<div class="flex flex-col gap-1">
    <label for="date_of_birth" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Date of birth</label>
    <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($customer?->date_of_birth)->format('Y-m-d')) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="contact_person_name" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Emergency contact person</label>
    <input id="contact_person_name" name="contact_person_name" value="{{ old('contact_person_name', $customer?->contact_person_name) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>
<div class="flex flex-col gap-1">
    <label for="contact_person_phone" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Emergency contact phone</label>
    <input id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone', $customer?->contact_person_phone) }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">
</div>

<div class="md:col-span-2 flex flex-col gap-1">
    <label for="profile_notes" class="text-xs font-semibold text-gray-600 dark:text-gray-400">Notes</label>
    <textarea id="profile_notes" name="profile_notes" rows="3" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900">{{ old('profile_notes', $customer?->profile_notes) }}</textarea>
</div>

<div class="md:col-span-2 flex gap-2">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save Customer</button>
    <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded">Cancel</a>
</div>

<script>
    document.querySelectorAll('.js-simple-employment-status').forEach(function (select) {
        function sync() {
            var form = select.closest('form');
            var status = select.value;
            form.querySelectorAll('.employment-simple-panel[data-show-when]').forEach(function (el) {
                var types = el.getAttribute('data-show-when').trim().split(/\s+/).filter(Boolean);
                var show = types.indexOf(status) !== -1;
                el.classList.toggle('hidden', !show);
                el.querySelectorAll('input, select, textarea').forEach(function (field) {
                    if (!field.name || field.type === 'hidden') return;
                    field.disabled = !show;
                });
            });
        }
        select.addEventListener('change', sync);
        sync();
    });
</script>
