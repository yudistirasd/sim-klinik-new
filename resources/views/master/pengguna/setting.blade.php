@extends('layouts.app')

@section('title', 'Setting Pengguna')

@section('subtitle', 'Master Data')

@section('content')
  <div class="row align-items-center">
    <div class="col-auto">
      <span class="avatar avatar-lg" style="background-image: url(https://ui-avatars.com/api/name={{ urlencode(Auth::user()->name_plain) }}?background=random)"> </span>
    </div>
    <div class="col">
      <h1 class="fw-bold m-0">{{ $pengguna->name }}</h1>
      <div class="list-inline list-inline-dots text-secondary fs-3">
        <div class="list-inline-item text-capitalize">
          <i class="ti ti-user fs-3"></i>
          {{ $pengguna->role }}
        </div>
        <div class="list-inline-item">
          <i class="ti ti-stethoscope fs-3"></i>
          <a href="https://satusehat.kemkes.go.id" target="_blank" class="text-reset">{{ $pengguna->ihs_id ?? '-' }}</a>
        </div>
        <div class="list-inline-item">
          <i class="ti ti-credit-card fs-3"></i>
          {{ $pengguna->nik ?? '-' }}
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-3" x-data="Setting" x-cloack>
    <div class="row g-0">
      <div class="col-12 col-md-2 border-end">
        <div class="card-body">
          <h4 class="subheader">Settings</h4>
          <div class="list-group list-group-transparent">
            <a href="./settings.html" class="list-group-item list-group-item-action d-flex align-items-center active">Ruangan</a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-10 d-flex flex-column">
        <div class="card-body">
          <h2 class="mb-4">Setting Ruangan</h2>
          <form @submit.prevent="handleSubmit">
            <div class="mb-3">
              <label class="form-label">Ruang / Klinik</label>
              <div class="row">
                <div class="col">
                  <select x-model="form.ruangan_id" id="" class="form-select" :class="{ 'is-invalid': errors.ruangan_id }">
                    <option value=""></option>
                    @foreach ($ruangan as $item)
                      <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback" x-text="errors.ruangan_id"></div>

                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-2 btn-icon" :disabled="loading" aria-label="Button">
                    <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                    <i class="ti ti-plus" x-show="!loading"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer bg-transparent mt-auto">
          <div class="btn-list justify-content-end">
            <a href="{{ route('master.pengguna.index') }}" class="btn btn-primary btn-2"> Selesai </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('Setting', () => ({
        title: '',
        form: {
          user_id: '{{ $pengguna->id }}',
          ruangan_id: null,
        },
        endPoint: '',
        errors: {},
        loading: false,

        handleSubmit() {
          this.loading = true;
          this.errors = {};

          $.ajax({
            url: route('api.master.pengguna.setting-ruangan.store', this.form.user_id),
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
            this.resetForm();
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
            user_id: '{{ $pengguna->id }}',
            ruangan_id: null,
          };
          this.errors = {};
        }
      }))
    })
  </script>
@endpush
