<form id="filterForm" action="{{ route('laporan.list-pekerjaan-staff') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">

    <label>Dari:</label>
    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" style="width: auto;">
    
    <label>Sampai:</label>
    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" style="width: auto;">

    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="{{ route('laporan.list-pekerjaan-staff') }}" class="btn btn-secondary">Reset</a>
</form>


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