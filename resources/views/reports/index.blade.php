<x-manager-shell title="Reports" header-title="Reports" header-subtitle="Admin summary reports">
<div class="panel">
  <div class="panel-title">Key Metrics</div>
  <div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px;margin-top:10px;">
    <div class="panel" style="padding:10px;"><div class="label">Customers</div><div style="font-size:18px;font-weight:800;">{{ $metrics['customers_total'] }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Properties</div><div style="font-size:18px;font-weight:800;">{{ $metrics['properties_total'] }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Reservations</div><div style="font-size:18px;font-weight:800;">{{ $metrics['reservations_total'] }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Payments total</div><div style="font-size:18px;font-weight:800;">PHP {{ number_format((float)$metrics['payments_amount_total'],2) }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Attendance today</div><div style="font-size:18px;font-weight:800;">{{ $metrics['attendance_today'] }}</div></div>
  </div>
</div>
<div class="panel">
  <div class="panel-title">Collections by Type</div>
  <div style="overflow:auto;border:1px solid #e2e8f0;border-radius:12px;background:#fff;margin-top:10px;">
    <table><thead><tr><th>Type</th><th>Count</th><th>Total Amount</th></tr></thead><tbody>
      @forelse($collectionsByType as $row)<tr><td>{{ str_replace('_',' ',$row->payment_type) }}</td><td>{{ $row->total_count }}</td><td>PHP {{ number_format((float)$row->total_amount,2) }}</td></tr>@empty<tr><td colspan="3" class="muted" style="text-align:center;padding:18px;">No payment data.</td></tr>@endforelse
    </tbody></table>
  </div>
</div>
</x-manager-shell>
