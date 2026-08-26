<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    //CRUD_ผู้ใช้

    public function UserList()
    {
        $user_list = DB::table('users')
        ->join('user_details','users.user_id','=','user_details.user_id')
        ->leftJoin('users as companies','users.user_dep','=','companies.user_id')
        ->whereIn('users.role',['company','leader'])
        ->select('users.*','user_details.*','users.user_id as user_id','companies.name as company_name')
        ->get();

        return view('admin.UserList',compact('user_list'));
    }

    public function CreateUser()
    {
        $company_list = DB::table('users')
        ->join('user_details','users.user_id','=','user_details.user_id')
        ->where('users.role','=','company')
        ->get();

        return view('admin.UserCreate',compact('company_list'));
    }

    public function InsertUser(Request $request)
    {
        $user_id = Str::upper(Str::random(10));
        $company_id = $request->user_role == 'leader' ? $request->company_id : null;

        if ($request->hasFile('user_logo')) {
            $file_input = $request->file('user_logo');
            $name_gen = hexdec(uniqid());
            $file_ext = strtolower($file_input->getClientOriginalExtension());
            $file_name = $name_gen . '.' . $file_ext;
            $upload_location = 'upload/';
            $full_path = $upload_location . $file_name;

            $file_input->move($upload_location, $file_name);

            DB::table('user_details')->insert([
                'user_id' => $user_id,
                'fullname' => $request->name,
                'user_logo' => $full_path,
                'user_status' => '1',
                'user_dep' => $company_id,
                'created_at' => Carbon::now()
            ]);
        }else
        {
            DB::table('user_details')->insert([
                'user_id' => $user_id,
                'fullname' => $request->name,
                'user_logo' => '0',
                'user_status' => '1',
                'user_dep' => $company_id,
                'created_at' => Carbon::now()
            ]);
        }

        DB::table('users')->insert([
            'user_id' => $user_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_2' => $request->password,
            'role'=>$request->user_role,
            'user_dep' => $company_id,
            'created_at' => Carbon::now()
        ]);
        return redirect()->route('admin_Userlist')->with('success', 'สร้างบัญชีผู้ใช้สำเร็จ');
    }

    public function UserDetail($id){
        $userdetail = DB::table('user_details')    
        ->where('user_id','=',$id)   
        ->orderBy('id','desc') 
        ->get();

        $user_dep = DB::table('users')
        ->where('users.user_dep','=',$id)
        ->orderBy('id','desc') 
        ->get();
        return view('admin.UserDetail',compact('userdetail','user_dep'));
    }

    public function CreateAgentUser($id){
        $userdetail = DB::table('user_details')    
        ->where('user_id','=',$id)    
        ->get();       
        return view('admin.UserAgentCreate',['id'=>$id],compact('userdetail'));        
    }

    public function AgentInsertUser(Request $request,$id){

        $user_id = Str::upper(Str::random(12));

        DB::table('user_details')->insert([
            'user_id' => $user_id,
            'fullname' => $request->name,
            'user_logo' => '0',
            'user_status' => '1',
            'user_dep'=> $id,
            'created_at' => Carbon::now()        
        ]);

        DB::table('users')->insert([
            'user_id' => $user_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_2' => $request->password,
            'role'=>$request->user_role,
            'user_dep'=>$id,
            'created_at' => Carbon::now()          
        ]);

        $userdetail = DB::table('user_details')    
        ->where('user_id','=',$id)    
        ->get();

         return redirect()->route('admin_UserDetail',['id'=>$id])
         ->with('userdetail', $userdetail)
         ->with('success', 'สร้างบัญชีผู้ใช้สำเร็จ');
             
    }

    public function AgentEdit($id)
    {
        $userdetail = DB::table('user_details')    
        ->where('user_id','=',$id)    
        ->get();

        return view('admin.AgentEdit',['id'=>$id],compact('userdetail'));     
    }

    public function AgentUpdate(Request $request,$id)
    {

        if ($request->hasFile('user_logo')) {
            $file_input = $request->file('user_logo');
            $name_gen = hexdec(uniqid());
            $file_ext = strtolower($file_input->getClientOriginalExtension());
            $file_name = $name_gen . '.' . $file_ext;
            $upload_location = 'upload/';
            $full_path = $upload_location . $file_name;

            $file_input->move($upload_location, $file_name);

            DB::table('user_details')->where('user_id','=',$id)
            ->update([
                'fullname' => $request->fullname,
                'user_logo' => $full_path,
                'updated_at' => Carbon::now()
            ]);

            DB::table('users')->where('user_id','=',$id)
            ->update([
                'name' => $request->fullname,
                'updated_at' => Carbon::now()
            ]);
          
        }else{
            DB::table('user_details')->where('user_id','=',$id)
            ->update([
                'fullname' => $request->fullname,
                'updated_at' => Carbon::now()
            ]);

            DB::table('users')->where('user_id','=',$id)
            ->update([
                'name' => $request->fullname,
                'updated_at' => Carbon::now()
            ]);
        }
    
        $userdetail = DB::table('user_details')    
        ->where('user_id','=',$id)    
        ->get();

         return redirect()->route('admin_UserDetail',['id'=>$id])
         ->with('userdetail', $userdetail)
         ->with('success', 'แก้ไขผู้ใช้สำเร็จ');

    }

    public function AgentDeleteUser(Request $request,$agent)
    {
        $id = $request->user_id;

        DB::table('users')->where('user_id','=',$id)->delete();
        DB::table('user_details')->where('user_id','=',$id)->delete();
        DB::table('chk_records')->where('user_id','=',$id)->delete();
        DB::table('detail_records')->where('user_id','=',$id)->delete();

        $userdetail = DB::table('user_details')    
        ->where('user_id','=',$agent)    
        ->get();

        return redirect()->route('admin_UserDetail',['id'=>$agent])
         ->with('userdetail', $userdetail)
         ->with('success', 'บันทึกข้อมูลสำเร็จ');  
    }

}
