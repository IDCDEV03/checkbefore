@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">สร้างฟอร์มใหม่</div>

                <div class="card-body">
                   
                    <form method="POST" action="{{route('admin_insert_form')}}">
                        @csrf
                        <div class="mb-3">
                          <label for="form_name" class="form-label">ชื่อฟอร์ม</label>
                          <input type="text" class="form-control" id="form_name" name="form_name">
                          
                        </div>

                        <div class="mb-3">
                          <label for="form_category" class="form-label">ประเภทฟอร์ม</label>
                          <select class="form-select" name="form_type">
                            <option selected disabled>-เลือก</option>
                            @foreach ($form_type as $item)
                            <option value="{{$item->id}}"> {{$item->form_type_name}} </option>
                            @endforeach                            
                          </select>
                        </div>

                        <div class="mb-3">
                          <label for="form_category" class="form-label">จำนวนหมวดหมู่</label>
                          <input type="number" class="form-control" id="form_category" name="form_category">
                          <div class="form-text">ระบุเป็นตัวเลขเท่านั้น</div>
                        </div>

                        <table class="table table-bordered" id="dynamicTable">
                          <thead>
                            <tr>
                             
                              <th scope="col">ชื่อหมวดหมู่</th>
                              <th scope="col">เพิ่ม/ลบ</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>                              
                              <td><input type="text" name="category_name[0]" placeholder="ระบุหมวดหมู่" class="form-control form-control-sm" /></td>
                              <td><button type="button" name="add" id="add" class="btn btn-primary btn-sm">เพิ่มหมวดหมู่</button></td>
                            </tr>                              
                          </tbody>
                        </table>
                      
                        <button type="submit" class="btn btn-sm btn-success">บันทึก</button>
                      </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">   
  var i = 0;       
  $("#add").click(function(){   
      ++i;   
      $("#dynamicTable").append('<tr><td><input type="text" name="category_name['+i+']" placeholder="ระบุหมวดหมู่" class="form-control form-control-sm" /></td><td><button type="button" class="btn btn-danger btn-sm remove-tr">ลบ</button></td></tr>');
  });
 
  $(document).on('click', '.remove-tr', function(){  
       $(this).parents('tr').remove();
  });  
 
</script>
@endsection
