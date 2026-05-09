<div class="section-title">Personal Information</div>

<div class="field">
    <label for="{{ $prefix }}first_name">First name</label>
    <input id="{{ $prefix }}first_name" name="first_name" value="{{ $isCreate ? old('first_name') : '' }}" required>
</div>
<div class="field">
    <label for="{{ $prefix }}last_name">Last name</label>
    <input id="{{ $prefix }}last_name" name="last_name" value="{{ $isCreate ? old('last_name') : '' }}" required>
</div>
<div class="field">
    <label for="{{ $prefix }}middle_name">Middle name</label>
    <input id="{{ $prefix }}middle_name" name="middle_name" value="{{ $isCreate ? old('middle_name') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}email">Email address</label>
    <input id="{{ $prefix }}email" name="email" type="email" value="{{ $isCreate ? old('email') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}phone">Mobile number</label>
    <input id="{{ $prefix }}phone" name="phone" value="{{ $isCreate ? old('phone') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}date_of_birth">Date of birth</label>
    <input id="{{ $prefix }}date_of_birth" name="date_of_birth" type="date" value="{{ $isCreate ? old('date_of_birth') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}civil_status">Civil status</label>
    <select id="{{ $prefix }}civil_status" name="civil_status">
        <option value="">Select civil status</option>
        @foreach($civilStatusOptions as $status)
            <option value="{{ $status }}" @selected($isCreate && old('civil_status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}lead_source">How did you hear about us?</label>
    <select id="{{ $prefix }}lead_source" name="lead_source">
        <option value="">Select source</option>
        <option value="walk_in"          @selected($isCreate && old('lead_source') === 'walk_in')>🚶 Walk-in</option>
        <option value="facebook"         @selected($isCreate && old('lead_source') === 'facebook')>📘 Facebook</option>
        <option value="office_to_office" @selected($isCreate && old('lead_source') === 'office_to_office')>🏢 Office to Office</option>
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}citizenship">Citizenship</label>
    <input id="{{ $prefix }}citizenship" name="citizenship" value="{{ $isCreate ? old('citizenship') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}tin_number">TIN number</label>
    <input id="{{ $prefix }}tin_number" name="tin_number" value="{{ $isCreate ? old('tin_number') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}sss_gsis_number">SSS or GSIS number</label>
    <input id="{{ $prefix }}sss_gsis_number" name="sss_gsis_number" value="{{ $isCreate ? old('sss_gsis_number') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}pagibig_mid_number">Pag-IBIG MID number</label>
    <input id="{{ $prefix }}pagibig_mid_number" name="pagibig_mid_number" value="{{ $isCreate ? old('pagibig_mid_number') : '' }}">
</div>

<div class="section-title">Present address</div>
<div class="field span-2">
    <label for="{{ $prefix }}present_address_street">Street / house no. / subdivision</label>
    <input id="{{ $prefix }}present_address_street" name="present_address_street" value="{{ $isCreate ? old('present_address_street') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}present_address_barangay">Barangay</label>
    <input id="{{ $prefix }}present_address_barangay" name="present_address_barangay" value="{{ $isCreate ? old('present_address_barangay') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}present_address_city">City / municipality</label>
    <input id="{{ $prefix }}present_address_city" name="present_address_city" value="{{ $isCreate ? old('present_address_city') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}present_address_province">Province</label>
    <input id="{{ $prefix }}present_address_province" name="present_address_province" value="{{ $isCreate ? old('present_address_province') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}present_address_zip">ZIP code</label>
    <input id="{{ $prefix }}present_address_zip" name="present_address_zip" value="{{ $isCreate ? old('present_address_zip') : '' }}">
</div>

<div class="js-spouse-section span-2" style="display:contents">
    <div class="section-title">Spouse Information</div>
    <div class="field">
        <label for="{{ $prefix }}spouse_first_name">Spouse first name</label>
        <input id="{{ $prefix }}spouse_first_name" name="spouse_first_name" value="{{ $isCreate ? old('spouse_first_name') : '' }}">
    </div>
    <div class="field">
        <label for="{{ $prefix }}spouse_middle_name">Spouse middle name</label>
        <input id="{{ $prefix }}spouse_middle_name" name="spouse_middle_name" value="{{ $isCreate ? old('spouse_middle_name') : '' }}">
    </div>
    <div class="field">
        <label for="{{ $prefix }}spouse_last_name">Spouse last name</label>
        <input id="{{ $prefix }}spouse_last_name" name="spouse_last_name" value="{{ $isCreate ? old('spouse_last_name') : '' }}">
    </div>
    <div class="field">
        <label for="{{ $prefix }}spouse_employment">Spouse employment type</label>
        <select id="{{ $prefix }}spouse_employment" name="spouse_employment">
            <option value="">Select employment type</option>
            @foreach($employmentOptions as $employment)
                <option value="{{ $employment }}" @selected($isCreate && old('spouse_employment') === $employment)>{{ ucfirst(str_replace('_', ' ', $employment)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="field">
        <label for="{{ $prefix }}spouse_monthly_income">Spouse monthly income (PHP)</label>
        <input id="{{ $prefix }}spouse_monthly_income" name="spouse_monthly_income" type="number" step="0.01" value="{{ $isCreate ? old('spouse_monthly_income') : '' }}">
    </div>
    <div></div>
</div>

<div class="section-title">Employment and Income</div>
<div class="field">
    <label for="{{ $prefix }}employment_status">Your employment type</label>
    <select id="{{ $prefix }}employment_status" name="employment_status" class="js-employment-status" required>
        @foreach($employmentOptions as $employment)
            <option value="{{ $employment }}" @selected($isCreate && old('employment_status', 'employed') === $employment)>{{ ucfirst(str_replace('_', ' ', $employment)) }}</option>
        @endforeach
    </select>
</div>

<div class="field employment-panel hidden" data-show-when="employed contractual">
    <label for="{{ $prefix }}employer_name">Company / employer name</label>
    <input id="{{ $prefix }}employer_name" name="employer_name" value="{{ $isCreate ? old('employer_name') : '' }}">
</div>
<div class="field employment-panel hidden" data-show-when="employed contractual">
    <label for="{{ $prefix }}job_title">Position / job title</label>
    <input id="{{ $prefix }}job_title" name="job_title" value="{{ $isCreate ? old('job_title') : '' }}">
</div>
<div class="field employment-panel hidden" data-show-when="employed contractual">
    <label for="{{ $prefix }}years_employed">Years employed</label>
    <input id="{{ $prefix }}years_employed" name="years_employed" type="number" min="0" value="{{ $isCreate ? old('years_employed') : '' }}">
</div>

<div class="field employment-panel hidden" data-show-when="self_employed">
    <label for="{{ $prefix }}business_name">Business name</label>
    <input id="{{ $prefix }}business_name" name="business_name" value="{{ $isCreate ? old('business_name') : '' }}">
</div>
<div class="field employment-panel hidden" data-show-when="self_employed">
    <label for="{{ $prefix }}business_nature">Nature of business</label>
    <input id="{{ $prefix }}business_nature" name="business_nature" value="{{ $isCreate ? old('business_nature') : '' }}">
</div>
<div class="field employment-panel hidden" data-show-when="self_employed">
    <label for="{{ $prefix }}years_in_business">Years in business</label>
    <input id="{{ $prefix }}years_in_business" name="years_in_business" type="number" min="0" value="{{ $isCreate ? old('years_in_business') : '' }}">
</div>

<div class="field employment-panel hidden" data-show-when="ofw">
    <label for="{{ $prefix }}ofw_employer_name">Employer abroad</label>
    <input id="{{ $prefix }}ofw_employer_name" name="ofw_employer_name" value="{{ $isCreate ? old('ofw_employer_name') : '' }}">
</div>
<div class="field employment-panel hidden" data-show-when="ofw">
    <label for="{{ $prefix }}ofw_country">Country of work</label>
    <input id="{{ $prefix }}ofw_country" name="ofw_country" value="{{ $isCreate ? old('ofw_country') : '' }}">
</div>

<div class="field span-2">
    <label for="{{ $prefix }}monthly_income">Monthly income (PHP)</label>
    <input id="{{ $prefix }}monthly_income" name="monthly_income" type="number" step="0.01" value="{{ $isCreate ? old('monthly_income') : '' }}">
</div>

<div class="section-title">Property and Financing</div>

<div class="field span-2">
    <label for="{{ $prefix }}selected_property_id">Property unit</label>
    <select id="{{ $prefix }}selected_property_id" name="selected_property_id" class="js-property-select">
        <option value="">Select property unit</option>
        <option value="customized" data-price="" data-lot-size="" data-floor-area="" data-lot-type="" data-house-model="Customized" data-street="">
            🏗 Customized Build
        </option>
        @foreach($properties as $property)
            <option
                value="{{ $property->id }}"
                data-price="{{ $property->price }}"
                data-lot-size="{{ $property->lot_size }}"
                data-floor-area="{{ $property->floor_area }}"
                data-lot-type="{{ $property->lot_type }}"
                data-house-model="{{ ucfirst($property->house_model) }}"
                data-street="{{ $property->street_name }}"
            >
                Block {{ $property->block_number }}, Lot {{ $property->lot_number }}
                — {{ ucfirst($property->house_model) }}
                ({{ $property->lot_type === 'corner_lot' ? 'Corner Lot' : 'Regular' }})
            </option>
        @endforeach
    </select>
</div>

{{-- Property info preview — hidden for customized --}}
<div class="field js-property-preview js-regular-only" style="display:none">
    <label>Lot type</label>
    <input class="js-preview-lot-type" readonly>
</div>
<div class="field js-property-preview js-regular-only" style="display:none">
    <label>Lot size</label>
    <input class="js-preview-lot-size" readonly>
</div>
<div class="field js-property-preview js-regular-only" style="display:none">
    <label>Floor area</label>
    <input class="js-preview-floor-area" readonly>
</div>
<div class="field js-property-preview js-regular-only" style="display:none">
    <label>Street</label>
    <input class="js-preview-street" readonly>
</div>
<div class="field js-property-preview js-regular-only" style="display:none">
    <label>Contract price (PHP)</label>
    <input class="js-preview-price" readonly>
</div>

{{-- Hidden input to submit contract price --}}
<input type="hidden" name="contract_price" id="{{ $prefix }}contract_price" class="js-contract-price-hidden">

<div class="field">
    <label for="{{ $prefix }}project_name">Project name</label>
    <input id="{{ $prefix }}project_name" name="project_name"
        value="{{ $isCreate ? old('project_name', 'Midland Valley Homes') : '' }}">
</div>
<div class="field span-2">
    <label>Financing type</label>
    <input value="Pag-IBIG" readonly>
    <input id="{{ $prefix }}preferred_financing_type" name="preferred_financing_type"
        type="hidden" value="pagibig">
</div>
<div class="field span-2">
    <label for="{{ $prefix }}monthly_amortization_estimate">Monthly amortization estimate (PHP)</label>
    <input id="{{ $prefix }}monthly_amortization_estimate" name="monthly_amortization_estimate"
        type="number" step="0.01"
        value="{{ $isCreate ? old('monthly_amortization_estimate') : '' }}">
</div>
<div class="field span-2">
    <label for="{{ $prefix }}affordability_notes">Affordability notes</label>
    <textarea id="{{ $prefix }}affordability_notes" name="affordability_notes"
        rows="2">{{ $isCreate ? old('affordability_notes') : '' }}</textarea>
</div>

<div class="section-title">Eligibility Review</div>
<div class="section-note">Marketing prepares the profile; Manager finalizes approval decisions.</div>
<div class="checkbox-grid">
    <label class="checkbox-item"><input id="{{ $prefix }}is_pagibig_member" name="is_pagibig_member" type="checkbox" value="1" @checked($isCreate && old('is_pagibig_member'))> Active Pag-IBIG Member</label>
    <label class="checkbox-item"><input id="{{ $prefix }}has_required_pagibig_contributions" name="has_required_pagibig_contributions" type="checkbox" value="1" @checked($isCreate && old('has_required_pagibig_contributions'))> Required Contributions Complete</label>
    <label class="checkbox-item"><input id="{{ $prefix }}has_stable_income" name="has_stable_income" type="checkbox" value="1" @checked($isCreate && old('has_stable_income'))> Stable Income Source</label>
    <label class="checkbox-item"><input id="{{ $prefix }}has_outstanding_debts" name="has_outstanding_debts" type="checkbox" value="1" @checked($isCreate && old('has_outstanding_debts'))> Has Major Unpaid Debts</label>
    <label class="checkbox-item"><input id="{{ $prefix }}has_downpayment_capacity" name="has_downpayment_capacity" type="checkbox" value="1" @checked($isCreate && old('has_downpayment_capacity'))> Downpayment Ready</label>
</div>
<div class="field span-2">
    <label for="{{ $prefix }}savings_amount">Savings amount (PHP)</label>
    <input id="{{ $prefix }}savings_amount" name="savings_amount" type="number" step="0.01" value="{{ $isCreate ? old('savings_amount') : '' }}">
</div>
<div class="field span-2">
    <label for="{{ $prefix }}qualification_notes">Qualification / eligibility notes</label>
    <textarea id="{{ $prefix }}qualification_notes" name="qualification_notes" rows="2">{{ $isCreate ? old('qualification_notes') : '' }}</textarea>
</div>

<div class="section-title">Document Checklist</div>
<div class="section-note">Documentation updates these checklist fields once the customer submits requirements.</div>
<div class="field">
    <label for="{{ $prefix }}valid_id_status">Valid IDs</label>
    <select id="{{ $prefix }}valid_id_status" name="valid_id_status">
        @foreach($documentStatuses as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}proof_of_billing_status">Proof of billing</label>
    <select id="{{ $prefix }}proof_of_billing_status" name="proof_of_billing_status">
        @foreach($documentStatuses as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}proof_of_income_status">Proof of income</label>
    <select id="{{ $prefix }}proof_of_income_status" name="proof_of_income_status">
        @foreach($documentStatuses as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}birth_certificate_status">Birth certificate</label>
    <select id="{{ $prefix }}birth_certificate_status" name="birth_certificate_status">
        @foreach($documentStatuses as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}marriage_certificate_status">Marriage certificate</label>
    <select id="{{ $prefix }}marriage_certificate_status" name="marriage_certificate_status">
        @foreach($documentStatuses as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}reservation_form_status">Reservation form</label>
    <select id="{{ $prefix }}reservation_form_status" name="reservation_form_status">
        @foreach($documentStatuses as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}financing_documents_status">Financing documents</label>
    <select id="{{ $prefix }}financing_documents_status" name="financing_documents_status">
        @foreach($documentStatuses as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="field span-2">
    <label for="{{ $prefix }}documentation_notes">Documentation / compliance notes</label>
    <textarea id="{{ $prefix }}documentation_notes" name="documentation_notes" rows="2">{{ $isCreate ? old('documentation_notes') : '' }}</textarea>
</div>

<div class="section-title">Workflow and Notes</div>
<div class="field span-2">
    <label for="{{ $prefix }}status">Workflow status</label>
    <select id="{{ $prefix }}status" name="status" required>
        @foreach($statusOptions as $status)
            <option value="{{ $status }}">{{ $customerStatuses[$status] }}</option>
        @endforeach
    </select>
</div>
<div class="field">
    <label for="{{ $prefix }}contact_person_name">Emergency contact person</label>
    <input id="{{ $prefix }}contact_person_name" name="contact_person_name" value="{{ $isCreate ? old('contact_person_name') : '' }}">
</div>
<div class="field">
    <label for="{{ $prefix }}contact_person_phone">Emergency contact number</label>
    <input id="{{ $prefix }}contact_person_phone" name="contact_person_phone" value="{{ $isCreate ? old('contact_person_phone') : '' }}">
</div>
<div class="field span-2">
    <label for="{{ $prefix }}profile_notes">Marketing profile notes</label>
    <textarea id="{{ $prefix }}profile_notes" name="profile_notes" rows="2">{{ $isCreate ? old('profile_notes') : '' }}</textarea>
</div>
<div class="field span-2">
    <label for="{{ $prefix }}manager_notes">Manager decision notes</label>
    <textarea id="{{ $prefix }}manager_notes" name="manager_notes" rows="2">{{ $isCreate ? old('manager_notes') : '' }}</textarea>
</div>

<script>
(function () {
    document.querySelectorAll('.js-property-select').forEach(function (select) {
        var form = select.closest('form');

        function sync() {
            var opt = select.options[select.selectedIndex];
            var isCustom = opt && opt.value === 'customized';
            var hasValue = opt && opt.value !== '';

            // Show/hide preview fields
            form.querySelectorAll('.js-regular-only').forEach(function (el) {
                el.style.display = (hasValue && !isCustom) ? '' : 'none';
            });

            // Fill hidden contract price input
            var hiddenPrice = form.querySelector('.js-contract-price-hidden');
            if (hiddenPrice) {
                hiddenPrice.value = (!isCustom && hasValue) ? (opt.dataset.price || '') : '';
            }

            if (!hasValue || isCustom) return;

            // Fill read-only preview inputs
            form.querySelector('.js-preview-lot-type').value   = opt.dataset.lotType === 'corner_lot' ? 'Corner Lot' : 'Regular';
            form.querySelector('.js-preview-lot-size').value   = opt.dataset.lotSize   || '';
            form.querySelector('.js-preview-floor-area').value = opt.dataset.floorArea || '';
            form.querySelector('.js-preview-street').value     = opt.dataset.street    || '';
            form.querySelector('.js-preview-price').value      = opt.dataset.price
                ? '₱' + parseFloat(opt.dataset.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })
                : '';
        }

        select.addEventListener('change', sync);
        sync();
    });
})();
</script>