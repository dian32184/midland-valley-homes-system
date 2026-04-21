@php
    $employmentOptions = [
        'employed' => 'Employed',
        'self_employed' => 'Self employed',
        'unemployed' => 'Unemployed',
        'contractual' => 'Contractual',
        'retired' => 'Retired',
    ];

    $statusOptions = [
        'pending' => 'Pending',
        'for_approval' => 'For approval',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">First name *</div>
        <input class="input" name="first_name" value="{{ old('first_name', $customer->first_name) }}" />
        @error('first_name') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Last name *</div>
        <input class="input" name="last_name" value="{{ old('last_name', $customer->last_name) }}" />
        @error('last_name') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Middle name</div>
        <input class="input" name="middle_name" value="{{ old('middle_name', $customer->middle_name) }}" />
        @error('middle_name') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Email</div>
        <input class="input" name="email" value="{{ old('email', $customer->email) }}" />
        @error('email') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Phone</div>
        <input class="input" name="phone" value="{{ old('phone', $customer->phone) }}" />
        @error('phone') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Date of birth</div>
        <input class="input" name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($customer->date_of_birth)->format('Y-m-d') ?? $customer->date_of_birth) }}" />
        @error('date_of_birth') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Address</div>
        <input class="input" name="address" value="{{ old('address', $customer->address) }}" />
        @error('address') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="panel" style="margin-top: 10px;">
    <div class="panel-head">
        <div class="panel-title">Employment</div>
        <div class="chip">Status + income</div>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
        <div class="field">
            <div class="label">Employment status *</div>
            <select class="select" name="employment_status">
                @foreach ($employmentOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('employment_status', $customer->employment_status ?? 'employed') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('employment_status') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Monthly income</div>
            <input class="input" name="monthly_income" type="number" step="0.01" min="0" value="{{ old('monthly_income', $customer->monthly_income) }}" />
            @error('monthly_income') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Customer status *</div>
            <select class="select" name="status">
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $customer->status ?? 'pending') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Employer name</div>
            <input class="input" name="employer_name" value="{{ old('employer_name', $customer->employer_name) }}" />
            @error('employer_name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field" style="grid-column: span 2;">
            <div class="label">Job title</div>
            <input class="input" name="job_title" value="{{ old('job_title', $customer->job_title) }}" />
            @error('job_title') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field" style="grid-column: 1 / -1;">
            <div class="label">Profile notes</div>
            <textarea class="textarea" name="profile_notes" rows="3">{{ old('profile_notes', $customer->profile_notes) }}</textarea>
            @error('profile_notes') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="panel" style="margin-top: 10px;">
    <div class="panel-head">
        <div class="panel-title">Emergency Contact</div>
        <div class="chip">Optional</div>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
        <div class="field">
            <div class="label">Contact person name</div>
            <input class="input" name="contact_person_name" value="{{ old('contact_person_name', $customer->contact_person_name) }}" />
            @error('contact_person_name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Contact person phone</div>
            <input class="input" name="contact_person_phone" value="{{ old('contact_person_phone', $customer->contact_person_phone) }}" />
            @error('contact_person_phone') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

