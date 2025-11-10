@extends('layouts.app')

@section('title', 'Pasien')
@section('subtitle', 'Registrasi')

@push('css')
  <link href="{{ asset('libs/datatables/dataTables.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
  <link href="{{ asset('libs/datatables/fixedHeader.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
  <link href="{{ asset('libs/datatables/responsive.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
@endpush

@section('action-page')
  <a href="{{ route('registrasi.pasien.create') }}" class="btn btn-primary btn-5">
    <div class="ti ti-plus me-1"></div>
    Pasien Baru
  </a>
@endsection

@section('content')
  <!-- Table -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped dataTable  table-hover" id="pasien-table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">NIK</th>
              <th class="text-center">No RM</th>
              <th class="text-center" style="width: 10%">Nama</th>
              <th class="text-center">Jenis Kelamin</th>
              <th class="text-center">Tempat & Tgl Lahir</th>
              <th class="text-center">Usia</th>
              <th class="text-center">Alamat</th>
              <th class="text-center">Aksi</th>
            </tr>
            <tr class="filter-row">
              <th></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari NIK"></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari No RM"></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari Nama"></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Jenis Kelamin"></th>
              <th></th>
              <th></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari Nama"></th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

@endsection


@push('js')
  <script src="{{ asset('libs/datatables/dataTables.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/dataTables.bootstrap5.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/dataTables.fixedHeader.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/dataTables.responsive.min.js') }}?{{ config('app.version') }}"></script>
  <script src="{{ asset('libs/datatables/responsive.bootstrap5.js') }}?{{ config('app.version') }}"></script>
  <script>
    const table = new DataTable('#pasien-table', {
      processing: true,
      serverSide: true,
      autoWidth: false,
      destroy: true,
      ajax: route('api.registrasi.pasien.dt'),
      orderCellsTop: true,
      initComplete: function() {
        this.api()
          .columns()
          .every(function() {
            const column = this;
            $('input', $('.filter-row th').eq(column.index()))
              .on('input', function() {
                column.search(this.value).draw();
              });
          });
      },
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          orderable: false,
          searchable: false,
          sClass: 'text-center',
          width: '5%'
        },
        {
          data: 'nik',
          name: 'nik',
          sClass: 'text-center'
        },
        {
          data: 'norm',
          name: 'norm',
          sClass: 'text-center'
        },
        {
          data: 'nama',
          name: 'nama',
          sClass: 'text-start'
        },
        {
          data: 'jenis_kelamin',
          name: 'jenis_kelamin',
          sClass: 'text-center'
        },
        {
          data: 'tempat_lahir',
          name: 'tempat_lahir',
          sClass: 'text-start'
        },
        {
          data: 'usia',
          name: 'usia',
          sClass: 'text-start'
        },
        {
          data: 'alamat',
          name: 'alamat',
          sClass: 'text-start'
        },
        {
          data: 'action',
          name: 'action',
          sClass: 'text-center',
          width: "15%"
        },
      ]
    });

    document.addEventListener('alpine:init', () => {
      Alpine.data('form', () => ({
        title: '',
        form: {
          id: null,
          name: '',
          ihs_id: ''
        },
        endPoint: '',
        errors: {},
        loading: false,

        modalControl(action, title, data = null) {
          this.resetForm();
          this.title = title;

          if (action == 'create') {
            delete this.form._method;
            this.endPoint = route('api.registrasi.pasien.store')
          }

          if (action == 'edit') {
            this.form = {
              ...data,
              _method: 'PUT'
            };

            this.endPoint = route('api.registrasi.pasien.update', data.id);
          }

          $('#modal-pasien').modal('show');

        },

        handleSubmit() {
          this.loading = true;
          this.errors = {};

          $.ajax({
            url: this.endPoint,
            method: 'POST',
            data: this.form,
            dataType: 'json',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            complete: () => {
              this.loading = false;
            }
          }).done((response) => {
            $('#modal-pasien').modal('hide');
            this.resetForm();
            table.ajax.reload();
            Toast.fire({
              icon: 'success',
              title: response.message
            });
          }).fail((error) => {
            if (error.status === 422) {
              this.errors = error.responseJSON.errors;
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan !',
                text: error.responseJSON.message
              });
            }
          })
        },

        resetForm() {
          this.form = {
            name: '',
            ihs_id: ''
          };
          this.errors = {};
        }
      }))
    })

    const handleModal = (action, title, data = null) => {
      const alpineComponent = Alpine.$data(document.querySelector('[x-data="form"]'));
      alpineComponent.modalControl(action, title, data);
    }
  </script>
@endpush
