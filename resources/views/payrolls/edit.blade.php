<x-manager-shell title="Edit Payroll" header-title="Payroll" header-subtitle="Update payroll">
<div class="panel"><form method="POST" action="{{ route('payrolls.update',$payroll) }}">@csrf @method('PUT') @include('payrolls._form',['payroll'=>$payroll,'employees'=>$employees])<div style="margin-top:10px;"><button class="btn btn-primary">Save</button></div></form></div>
</x-manager-shell>
