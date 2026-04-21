<x-manager-shell title="Add Payroll" header-title="Payroll" header-subtitle="Create payroll">
<div class="panel"><form method="POST" action="{{ route('payrolls.store') }}">@csrf @include('payrolls._form',['payroll'=>$payroll,'employees'=>$employees])<div style="margin-top:10px;"><button class="btn btn-primary">Save</button></div></form></div>
</x-manager-shell>
