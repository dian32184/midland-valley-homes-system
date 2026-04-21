<x-manager-shell title="Add Benefit" header-title="Benefits" header-subtitle="Create benefit">
<div class="panel"><form method="POST" action="{{ route('benefits.store') }}">@csrf @include('benefits._form',['benefit'=>$benefit,'employees'=>$employees])<div style="margin-top:10px;"><button class="btn btn-primary">Save</button></div></form></div>
</x-manager-shell>
