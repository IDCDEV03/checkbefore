@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary mb-3">
                <div class="card-header">สร้างบัญชีผู้ใช้ใหม่</div>

                <div class="card-body text-primary">
                    <form method="POST" action="{{ route('admin_InsertUser') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="formFileSm" class="col-md-4 col-form-label text-md-end">Logo หน่วยงาน (ถ้ามี)</label>
                            <div class="col-md-6">
                            <input class="form-control form-control-sm" name="user_logo" id="formFileSm" type="file" accept="image/*">
                            </div>
                          </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">ชื่อ-นามสกุล</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" required autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Username</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" required >

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="text" class="form-control" name="password" required >

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="Role" class="col-md-4 col-form-label text-md-end">Role</label>
                            <div class="col-md-6">
                            <select class="form-select" id="role" name="user_role" onchange="toggleCompanySelect()">
                                <option selected disabled>--เลือก--</option>
                                <option value="company">หน่วยงาน</option>
                                <option value="leader">เจ้าหน้าที่</option>
                              </select>
                            </div>
                        </div>

                        <div class="row mb-3" id="company_select_row" style="display:none;">
                            <label for="company_id" class="col-md-4 col-form-label text-md-end">หน่วยงานที่สังกัด</label>
                            <div class="col-md-6">
                            <select class="form-select" id="company_id" name="company_id">
                                <option value="" selected disabled>--เลือกหน่วยงาน--</option>
                                @foreach ($company_list as $company)
                                    <option value="{{ $company->user_id }}">{{ $company->name }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   บันทึก
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCompanySelect() {
        var role = document.getElementById('role').value;
        var row = document.getElementById('company_select_row');
        var select = document.getElementById('company_id');
        if (role === 'leader') {
            row.style.display = '';
            select.setAttribute('required', 'required');
        } else {
            row.style.display = 'none';
            select.removeAttribute('required');
        }
    }
</script>
@endsection
