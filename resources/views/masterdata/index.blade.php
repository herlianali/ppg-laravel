@extends('layouts.app')

@section('tittle', 'Data Master')

@section('content')
    {{-- <p>This is my Data Master Sister PPG.</p> --}}
<!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tabel /</span> Data Mahasiswa</h4>

              <!-- Bordered Table -->
              <div class="card">
                <h5 class="card-header">Bordered Table</h5>
                <div class="card-body">
                  <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>No UKG</th>
                          <th>Nama</th>
                          <th>Bidang Study</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>12345678</td>
                          <td>Angga Rifki SP</td>
                          <td>Pendidikan Guru Anak Usia Dini</td>
                          <td><span class="badge bg-label-success me-1">Completed</span></td>
                          <td>
                            <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              Lihat <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                          </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!--/ Bordered Table -->
            </div>
            <!-- / Content -->
@endsection