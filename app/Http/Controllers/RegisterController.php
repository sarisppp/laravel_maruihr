<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Register;
use App\Models\Course;
use App\Models\User;
use App\Models\LineToken;
use Illuminate\Support\Arr;
use PhpParser\JsonDecoder;
use Illuminate\Support\Facades\Storage;
class RegisterController extends Controller
{
  
    public function notifyMessage(Request $request){
     
        $lineToken = LineToken::first();
        $course = Course::find($request->idcourse);
        $user = User::find($request->iduser);
        $word = 'แจ้งลงทะเบียนอบรม'."\n" .'คุณ '. $user->firstname.' '.$user->lastname."\n".'รหัสพนักงาน '. $user->employeeid ."\n"."ตำแหน่ง: ".$user->position."\n"
                ."แผนก: ".$user->department."\n\n".'คอร์ส: '.$course->course."\n"
                ."จำนวนผู้เข้าร่วมที่: ".$course->registed."/ ".$course->limited ;
        // $a = Storage::get('public\image\defualt.png');
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->withToken($lineToken->token)->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => $word,
            // 'imageThumbnail' => 'http://localhost:8000/storage/image/defualt.png' ,
            // 'imageFullsize' => 'http://localhost:8000/storage/image/defualt.png',
        ]);
       
        return  $response;
    }


    public function add(Request $request)
    {   
    
        $course = Course::find($request->idcourse);
        $user = User::find($request->iduser);
        
            Register::create([
                'idcourse' => $course->_id,
                'iduser' => $user->_id,
                'status' => 'ลงทะเบียน',
            ]);
        $course->increment('registed');
        response()->json(["result" => "ok"], 201);
   
    }

    public function deleteRegister(Request $request)
    {   $data = $request->all();
        foreach($data as $key => $value){
            Register::where('iduser',$value['_id'])->where('idcourse',$value['course'])->delete();
            Course::find($value['course'])->decrement('registed');
        }
        return response()->json(["result" => "ok"], 200);

    }

    public function addUser(Request $request)
    {
        $user = User::where("employeeid",$request->_id)->get();
        if(count($user)==0){
            return response()->json(['error' => 'รหัสพนักงานไม่ถูกต้อง '], 400);
        }
        else {
            $checkDouble = Register::where("idcourse",$request->course)->where("iduser",$user[0]->id)->get();
            if(count($checkDouble)==0){
                $course = Course::find($request->course);
                Register::create([
                    'idcourse' => $course->_id,
                    'iduser' => $user[0]->_id,
                    'status' => 'ลงทะเบียน',
                ]);
                $course->increment('registed');
                return response()->json(["result" => "ok"], 201);
            }
            else{
                return response()->json(['error' => 'รหัสพนักงานซ้ำ ไม่สามารถเพิ่มได้'], 400);
            }
       
        }
        
    }
    public function update(Request $request){
        $data = $request->all();
        foreach($data as $key => $value){
            Register::where('iduser',$value['_id'])->where('idcourse',$value['course'])->update([
                'idcourse'=>$value['course'],
                'iduser'=>$value['_id'],
                'status'=>$value['status']
            ]);
        }
        return response()->json(["result" => "ok"], 200);
        // return response()->json($registerId);
       

    }

    public function show(Request $request)
    {
        $course = Register::all();

        return response()->json($course);
    }


    public function CoureUser($id){

        $register = Register::where('iduser',$id)->orderby('_id','desc')->get();

        if(count($register)==0){
            return response()->json(["error" => "ไม่พบข้อมูลการลงทะเบียน"], 400);
        }
        else{
            for ($i = 0; $i < count($register); $i++) {
                $registerUser[$i] = $register[$i]->course;
                $registerUser[$i]->status  = $register[$i]->status;
                $registerUser[$i]->idregister = $register[$i]->_id;
                // $c[$i]['status'] = $b->status;
            }
            
            return response()->json($registerUser,200);
    }
    }
    //for profile
    public function showCourseUser()
    {
        $user = auth()->user();
     
        $register = Register::where('iduser', $user['_id'])->orderby('_id','desc')->get();

        if(count($register)==0){
            return response()->json(["error" => "ไม่พบข้อมูลการลงทะเบียน"], 400);
        }
        else{
            for ($i = 0; $i < count($register); $i++) {
                $registerUser[$i] = $register[$i]->course;
                $registerUser[$i]->status  = $register[$i]->status;
                $registerUser[$i]->idregister = $register[$i]->_id;
                // $c[$i]['status'] = $b->status;
            }
            
            return response()->json($registerUser);
        }
       

        // $data = Register::where('iduser',$user['_id'])->get();
        // $data = Course::find('615185595d030000a9004e74')->register;   

        // foreach($b as $i){
        //     $c[]=$i->course;
        // }
        // return response()->json($b);
    }

    public function showRegistration($id)
    {   
        $register = Register::where('idcourse', $id)->get();
        if(count($register)==0){
            return response()->json(["error" => "ไม่พบข้อมูลการลงทะเบียน"], 400);
        }
        else{
            for ($i = 0; $i < count($register); $i++) {
                $registerUser[$i] = $register[$i]->users;
                $registerUser[$i]->status  = $register[$i]->status;
                $registerUser[$i]->idregister = $register[$i]->_id;
            }
            return response()->json($registerUser);
        }  
       
    }

    public function deletecourse($id)
    {
        $post = Register::find($id);
        $post::find($id)->delete();
        Course::find($post->idcourse)->decrement('registed', 1);
        
        return response()->json(["result" => "ok"], 200);
    }
}
