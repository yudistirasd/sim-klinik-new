@extends('layouts.app')

@section('title', 'Stok Obat')
@section('subtitle', 'Farmasi')

@push('css')
  <link href="{{ asset('libs/select2/select2.css') }}" rel="stylesheet" />
  <link href="{{ asset('libs/select2/select2-bootstrap-5-theme.css') }}" rel="stylesheet" />
  <link href="{{ asset('libs/datatables/dataTables.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
  <link href="{{ asset('libs/datatables/fixedHeader.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
  <link href="{{ asset('libs/datatables/responsive.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
@endpush

@section('content')
  <!-- Table -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="stok-table">
          <thead>
            <tr>
              <th rowspan="2" class="text-center">#</th>
              <th rowspan="2" class="text-center">Obat</th>
              <th rowspan="2" class="text-center">Barcode / Batch</th>
              <th rowspan="2" class="text-center">Expired Date</th>
              <th rowspan="2" class="text-center">Harga Beli</th>
              <th colspan="3" class="text-center">Harga Jual</th>
              <th rowspan="2" class="text-center">Ready</th>
            </tr>
            <tr>
              <th class="text-center">Harga Jual Resep</th>
              <th class="text-center">Harga Jual Bebas</th>
              <th class="text-center">Harga Jual Apotek</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

@endsection


@push('js')
  <script src="{{ asset('libs/select2/select2.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/select2/select2-searchInputPlaceholder.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/dataTables.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/dataTables.bootstrap5.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/dataTables.fixedHeader.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/dataTables.responsive.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/responsive.bootstrap5.js') }}?{{ config('app.version') }}"></script>
  <script>
    const table = new DataTable('#stok-table', {
      processing: true,
      serverSide: true,
      autoWidth: false,
      destroy: true,
      ajax: route('api.farmasi.stok-obat.dt'),
      order: [
        [
          1, 'asc'
        ]
      ],
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          orderable: false,
          searchable: false,
          sClass: 'text-center',
          width: '5%'
        },
        {
          data: 'name',
          name: 'name',
          sClass: 'text-start'
        },
        {
          data: 'barcode',
          name: 'barcode',
          sClass: 'text-center'
        },
        {
          data: 'expired_date',
          name: 'expired_date',
          sClass: 'text-start'
        },
        {
          data: 'harga_beli',
          name: 'harga_beli',
          sClass: 'text-end'
        },
        {
          data: 'harga_jual_resep',
          name: 'harga_jual_resep',
          sClass: 'text-end'
        },
        {
          data: 'harga_jual_bebas',
          name: 'harga_jual_bebas',
          sClass: 'text-end'
        },
        {
          data: 'harga_jual_apotek',
          name: 'harga_jual_apotek',
          sClass: 'text-end'
        },
        {
          data: 'ready',
          name: 'ready',
          sClass: 'text-center'
        },
      ]
    });
  </script>
@endpush
