@extends('layouts.app')

@section('title', 'Kunjungan Pasien')
@section('subtitle', 'Daftar')

@push('css')
  <link href="{{ asset('libs/datatables/dataTables.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
  <link href="{{ asset('libs/datatables/fixedHeader.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
  <link href="{{ asset('libs/datatables/responsive.bootstrap5.min.css') }}?{{ config('app.version') }}" rel="stylesheet">
@endpush

@section('content')
  <!-- Table -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table dataTable table-striped table-sm  table-hover" id="pasien-table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Tanggal Registrasi</th>
              <th class="text-center">No RM</th>
              <th class="text-center">No Registrasi</th>
              <th class="text-center">Nama Pasien</th>
              <th class="text-center">Usia</th>
              <th class="text-center">Alamat</th>
              <th class="text-center">Ruangan / Klinik</th>
              <th class="text-center">Dokter</th>
              <th class="text-center">Aksi</th>
            </tr>
            <tr class="filter-row">
              <th></th>
              <th><input type="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}"></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari"></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari"></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari"></th>
              <th></th>
              <th></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari"></th>
              <th><input type="text" class="form-control form-control-sm" placeholder="Cari"></th>
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
      searchDelay: 500,
      ajax: route('api.registrasi.kunjungan.dt'),
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
          data: 'tanggal_registrasi',
          name: 'tanggal_registrasi',
          sClass: 'text-center'
        },
        {
          data: 'pasien.norm',
          name: 'pasien.norm',
          sClass: 'text-center'
        },
        {
          data: 'noregistrasi',
          name: 'noregistrasi',
          sClass: 'text-center'
        },
        {
          data: 'pasien.nama',
          name: 'pasien.nama',
          sClass: 'text-start'
        },
        {
          data: 'pasien.usia',
          name: 'pasien.usia',
          sClass: 'text-start',
          searchable: false,
          orderable: false
        },
        {
          data: 'alamat',
          name: 'alamat',
          sClass: 'text-start',
          searchable: false,
          orderable: false
        },
        {
          data: 'ruangan.name',
          name: 'ruangan.name',
          sClass: 'text-center'
        },
        {
          data: 'dokter.name',
          name: 'dokter.name',
          sClass: 'text-start'
        },
        {
          data: 'action',
          name: 'action',
          sClass: 'text-center',
          width: "10%"
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
