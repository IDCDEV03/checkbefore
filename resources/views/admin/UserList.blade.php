@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">ผู้ดูแลระบบ</div>

                    <div class="card-body">

                        <p class="mb-4">
                            <a href="{{ route('admin_CreateUser') }}" class="btn btn-outline-primary">
                                สร้างบัญชีหน่วยงานใหม่</a>
                        </p>
                        <div class="table-responsive">
                            <table class="table cell-border hover" id="dataTables">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th>Logo</th>
                                        <th scope="col">หน่วยงาน/ชื่อผู้ใช้</th>
                                        <th scope="col">ระดับ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user_list as $item)
                                        <tr>
                                            <th scope="row">{{$loop->iteration}}</th>
                                            <td>
                                                @if ($item->user_logo == '0')
                                                    <img src="{{ asset('upload/no_img.jpg') }}" width="70px"
                                                        alt="">
                                                @else
                                                    <img src="{{ asset($item->user_logo) }}" width="70px" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin_UserDetail',['id'=>$item->user_id])}}" class="text-decoration-none">
                                                    {{ $item->name }}<i class="las la-pen"></i>
                                                </a>
                                            </td>
                                            <td>
                                                @if ($item->role == 'company')
                                                   องค์กร/หน่วยงาน
                                                @elseif($item->role == 'leader')
                                                    หัวหน้าฝ่าย
                                                    @elseif($item->role == 'user')
                                                    พนักงานทั่วไป
                                                @endif</td>
                                            <td>

                                                <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="ปิดการใช้งาน"
                                                    onclick="return confirm('ยืนยันการลบหรือไม่?')">
                                                    <i class="las la-times-circle"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
