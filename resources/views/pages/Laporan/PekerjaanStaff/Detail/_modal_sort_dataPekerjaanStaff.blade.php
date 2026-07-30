@php
    $encryptedId = \Illuminate\Support\Facades\Crypt::encryptString($datas['user']->id);
@endphp

<form id="filterForm" action="{{ route('laporan.detail-pekerjaan-staff', $encryptedId) }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
    <label>Dari:</label>
    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" style="width: auto;">
    
    <label>Sampai:</label>
    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" style="width: auto;">

    <button type="submit" class="btn btn-primary">Filter</button>


</form>

<a href="{{ route('laporan.detail-pekerjaan-staff', [$encryptedId, 'sort' => 'default']) }}" class="btn btn-primary">Reset</a>


<a href="{{ route('laporan.detail-pekerjaan-staff.export', $encryptedId) }}" class="btn btn-success">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel me-1" viewBox="0 0 16 16">
        <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
    </svg>
    Export Excel
</a>

<script>
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    // When "Dari" changes, update the minimum allowed date for "Sampai"
    startDate.addEventListener('change', function() {
        if (startDate.value) {
            endDate.min = startDate.value;
        }
    });

    // When "Sampai" changes, update the maximum allowed date for "Dari"
    endDate.addEventListener('change', function() {
        if (endDate.value) {
            startDate.max = endDate.value;
        }
    });
</script>