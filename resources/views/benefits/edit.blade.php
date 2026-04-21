<x-manager-shell title="Edit Benefit" header-title="Benefits" header-subtitle="Update benefit">
<div class="panel"><form method="POST" action="{{ route('benefits.update',$benefit) }}">@csrf @method('PUT') @include('benefits._form',['benefit'=>$benefit,'employees'=>$employees])<div style="margin-top:10px;"><button class="btn btn-primary">Save</button></div></form></div>
</x-manager-shell>
