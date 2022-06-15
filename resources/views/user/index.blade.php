@extends('layouts.master')
{{-- begin::section --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-end">
                {{-- begin::button-add --}}
                <button class="btn btn-light-primary" id="btnAdd">
                    <i class="fa fa-plus me-3"></i>
                    Tambah
                </button>
                {{-- end::button-add --}}
            </div>
        </div>
    </div>
    {{-- end::card-action --}}
    {{-- begin::card-list --}}
    <div class="card card-flush">
        <div class="card-body">
            {{-- begin::table --}}
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="dt_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th></th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600">
                    
                </tbody>
                <!--end::Table body-->
            </table>
            {{-- end::table --}}
        </div>
    </div>
    {{-- end::card-list --}}

    {{-- begin::modal --}}
    <div class="modal" tabindex="-1" id="modalUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formUser">
                    <div class="modal-body">
                        <div class="form-group mb-5 row">
                            <label for="userName" class="col-form-label">Nama</label>
                            <input type="text" class="form-control" placeholder="Nama Lengkap" id="userName" name="name">
                        </div>
                        <div class="form-group mb-5 row">
                            <label for="userEmail" class="col-form-label">Email</label>
                            <input type="text" class="form-control" id="userEmail" name="email" placeholder="Email Aktif Pengguna">
                        </div>
                        <div class="form-group mb-5 row">
                            <label for="userUsername" class="col-form-label">Username</label>
                            <input type="text" class="form-control" id="userUsername" name="username" placeholder="Username Untuk Login">
                        </div>
                        <div class="form-group mb-5 row">
                            <label for="userTpu" class="col-form-label">TPU</label>
                            <select name="tpu" id="userTpu" class="form-select form-control"></select>
                        </div>
                        <div class="form-group mb-5 row">
                            <label for="userRole" class="col-form-label">Role</label>
                            <select name="role" id="userRole" class="form-select form-control"></select>
                        </div>
                        <div class="form-group mb-5 row">
                            <div class="col-md-6">
                                <label for="userPassword" class="col-form-label">Password</label>
                                <input type="password" class="form-control" id="userPassword" name="password" placeholder="Kata Sandi User">
                            </div>
                            <div class="col-md-6">
                                <label for="userRePassword" class="col-form-label">Ulangi Password</label>
                                <input type="password" class="form-control" id="userRePassword" placeholder="Ulangi Kata Sandi User">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button class="btn btn-primary" id="btnSave" onclick="save()" type="button">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end::modal --}}
@endsection
{{-- begin::end --}}

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var _columns = [{
            data: "name"
        },{
            data: "username"
        },{
            data: "email"
        },{
            data: "role"
        },{
            data: 'action'
        }];
    
        let dataTables = $("#dt_table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{ route('user.json') }}",
            columns: _columns,
        });

        const modalUser = document.getElementById('modalUser')
        modalUser.addEventListener('hidden.bs.modal', event => {
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Simpan');
            // manipulate password field
            $('#userPassword').attr('placeholder', 'Kata Sandi User');
            $('#userRePassword').attr('placeholder', 'Ulangi Kata Sandi User');
            document.getElementById('formUser').reset();
        })

        // variable
        let form = $('#formUser');
        let elem = $('#btnSave');
        let modal = $('#modalUser');
        $('#btnAdd').on('click', function(e) {
            e.preventDefault();

            // get role list
            $.ajax({
                type: "GET",
                url: "{{ route('user.getDataForm') }}",
                dataType: "json",
                success: function(res) {
                    // begin::data-role
                    let dataRoles = res.data.roles;
                    let option = "<option value=''>- Pilih Role -</option>";
                    for (let a = 0; a < dataRoles.length; a++) {
                        option += `<option value="${dataRoles[a].name}">${dataRoles[a].name}</option>`;
                    }
                    $('#userRole').html(option);
                    $('#userRole').select2({
                        dropdownParent: modal
                    });
                    // end::data-role

                    // begin::data-tpu
                    let dataTpu = res.data.tpus;
                    let optionTpu = "<option value=''>- Pilih TPU -</option>";
                    optionTpu += "<option value='0'>Semua TPU</option>";
                    for (let b = 0; b < dataTpu.length; b++) {
                        optionTpu += `<option value="${dataTpu[b].id}">${dataTpu[b].name}</option>`;
                    }
                    $('#userTpu').html(optionTpu);
                    $('#userTpu').select2({
                        dropdownParent: modal
                    });
                    // end::data-tpu

                    $('#modalTitle').text('Tambah User');
                    form.attr('action', "{{ route('user.store') }}");
                    form.attr('method', 'POST');
                    modal.modal('show');

                },
                error: function(err) {
                    handleError(err)
                }
            })
        });

        function save() {
            let data = $('#formUser').serialize();
            let url = form.attr('action');
            let method = form.attr('method');
            if ($('#userPassword').val().toLowerCase() != $('#userRePassword').val().toLowerCase()) {
                iziToast['error']({
                    message: 'Password tidak sama',
                    position: "topRight"
                });
            } else {
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    dataType: "json",
                    beforeSend: function() {
                        elem.attr('disabled', true);
                        elem.text('Menyimpan data ...');
                    },
                    success: function(res) {
                        console.log(res);
                        elem.attr('disabled', false);
                        elem.text('Simpan');
                        iziToast['success']({
                            message: 'User berhasil di simpan',
                            position: "topRight"
                        });
    
                        modal.modal("hide");
                        dataTables.ajax.reload();
                        document.getElementById('formUser').reset();
                    },
                    error: function(err) {
                        handleError(err, elem);
                    }
                })
            }
        }

        function edit(id) {
            let url = '{{ url('/user/') }}' + '/' + id;

            $.ajax({
                type: "GET",
                url: "{{ url("/user/") }}" + "/" + id,
                dataType: 'json',
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    form.attr('action', url);
                    form.attr('method', 'POST');
                    modal.modal('show');
                    let user = res.data.user;
                    $('#userName').val(user.name);
                    $('#userEmail').val(user.email);
                    $('#userUsername').val(user.username);

                    // begin::data-tpu
                    let dataTpu = res.data.tpus;
                    let optionTpu = "<option value=''>- Pilih TPU -</option>";
                    optionTpu += "<option value='0'>Semua TPU</option>";
                    let selectedTpu = "";
                    for (let b = 0; b < dataTpu.length; b++) {
                        if (dataTpu[b].id == user.tpu_id) {
                            selectedTpu = 'selected';
                        } else {
                            selectedTpu = '';
                        }
                        optionTpu += `<option ${selectedTpu} value="${dataTpu[b].id}">${dataTpu[b].name}</option>`;
                    }
                    $('#userTpu').html(optionTpu);
                    $('#userTpu').val(user.tpu_id);
                    $('#userTpu').select2({
                        dropdownParent: modal
                    });
                    // end::data-tpu

                    // manipulate role select option
                    let roles = res.data.roles;
                    let option = `<option value="">- Pilih Role -</option>`;
                    let selectedRole = "";
                    for (let a = 0; a < roles.length; a++) {
                        if (roles[a].name.toLowerCase() == user.role.toLowerCase()) {
                            selectedRole = 'selected';
                        } else {
                            selectedRole = '';
                        }
                        option += `<option ${selectedRole} value="${roles[a].name}">${roles[a].name}</option>`;
                    }
                    $('#userRole').html(option);
                    $('#userRole').select2({
                        dropdownParent: modal
                    });
                    $('#userRole').val(user.role);

                    // manipulate password field
                    $('#userPassword').attr('placeholder', 'Kosongkan bila tidak dirubah');
                    $('#userRePassword').attr('placeholder', 'Kosongkan bila tidak dirubah');

                    $('#modalTitle').text('Edit User');
                },
                error: function(err) {
                    console.log(err);
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    let message = err.responseJSON.message;
                    if (message == 'FAILED') {
                        iziToast['error']({
                            message: err.responseJSON.data.error,
                            position: "topRight"
                        });
                    } else {
                        iziToast['error']({
                            message: message,
                            position: "topRight"
                        });
                    }
                }
            })
        }

        function deleteUser(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus user ini?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Hapus',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/user/') }}" + "/" + id,
                        success: function(res) {
                            iziToast['success']({
                                message: 'User berhasil di hapus',
                                position: "topRight"
                            });

                            dataTables.ajax.reload();
                        },
                        error: function(err) {
                            handleError(err);
                        }
                    })
                }
            })
        }
    </script>
@endpush