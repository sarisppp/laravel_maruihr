<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Register;
use App\Models\Course;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;

use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'register', 'adduser']]);
  }

  public function register(RegisterRequest $request)
  {
    $user = User::create([
      'email' => $request->email,
      'password' => bcrypt($request->password),
      'firstname' => $request->firstname,
      'lastname' => $request->lastname,
      'department' => $request->department,
      'employeeid' => $request->employeeid,
      'role' => $request->role,
    ]);

    // return $this->respondWithToken($token);

    // User::create($request->all());
    $token = auth()->login($user);
    //return $this->respondWithToken($token);
    return $this->login($request);
  }

  public function adduser(Request $request)
  {
    $checkid = User::where("employeeid",$request->employeeid)->get();
    $checkemail = User::where("email",$request->email)->get();
    if(count($checkid)!=0 || count($checkemail)!=0 ){  
      if(count($checkid)!=0){
        return response()->json(["message" => "มีผู้ใช้รหัสพนักงานนี้แล้ว"], 400);
      }
      if(count($checkemail)!=0){
        return response()->json(["message" => "มีผู้ใช้อีเมลล์นี้แล้ว"], 400);
      }
     
    }
    else{
      User::create([
        'title' => $request->title,
        'position' => $request->position,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'department' => $request->department,
        'employeeid' => $request->employeeid,
        'image' => 'defualt.png',
        'role' => $request->role,
      ]);
      return response()->json(["result" => "ok"], 201);

    }

  }


  public function delete($data)
  {

    $user = User::find($data);

    $register = Register::where('iduser', $data)->get();
    if (count($register) != 0) {
      for ($i = 0; $i < count($register); $i++) {
        Course::find($register[$i]->idcourse)->decrement('registed', 1);
        $register->find($register[$i]->_id)->delete();
      }
      $user::find($data)->delete();
      return response()->json(["result" => "ok"], 200);
    } else {
      $user::find($data)->delete();
      return response()->json(["result" => "ok"], 200);
    }
  }

  public function resetpassword(Request $request)
  {

    $credentail = request(['email', 'password']);

    if (!$token = auth()->attempt($credentail)) {
      return response()->json(['error' => 'รหัสผ่านไม่ถูกต้อง '], 401);
    } else {
      $user = User::whereEmail($request->email)->first();
      $user->update(['password' => bcrypt($request->newpassword)]);
      return response()->json(['data' => 'เปลี่ยนรหัสผ่านสำเร็จ'], 200);
    }
  }

  public function updateUser(Request $request, $id)
  {
    $post = User::find($id);
    $post::find($id)->update([
      'title' => $request->title,
      'position' => $request->position,
      'email' => $request->email,
      // 'password' => bcrypt($request->password),
      'firstname' => $request->firstname,
      'lastname' => $request->lastname,
      'department' => $request->department,
      'employeeid' => $request->employeeid,
      'role' => $request->role,
    ]);

    return response()->json(["result" => "ok"], 201);
  }

  public function deletes(Request $request)
  {
    $data = $request->all();
    // echo print_r($data);
    foreach ($data as $key => $value) {
      $post = User::find($value['_id']);
      $register = Register::where('iduser', $post['_id'])->get();
      if (count($register) != 0) {
        for ($i = 0; $i < count($register); $i++) {
          Course::find($register[$i]->idcourse)->decrement('registed', 1);
          $register->find($register[$i]->_id)->delete();
        }
      }

      $post->delete();
    }

    return response()->json(["result" => "ok"], 200);
  }
  /**
   * Get an User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function show()
  {

    // $user = User::all();
    $user = User::orderBy('created_at', 'desc')->get();
    return response()->json($user);
  }


  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login()
  {
    $credentials = request(['email', 'password']);
    //return response()->json($credentials);
    if (!$token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'ยูสเซอร์ไอดีและรหัสผ่านไม่ถูกต้อง '], 401);
    }

    return $this->respondWithToken($token);
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return response()->json(auth()->user());
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth()->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return $this->respondWithToken(auth()->refresh());
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token)
  {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ]);
  }

  public function updateCourse(Request $request, $id)
  {
    // $data = User::find($id)                                ok
    //         ->push('courses',$request->courses);
    $data = User::where('_id', $id)->whereIn('courses', array('อบรมพนักงานใหม่', 'อบรมพนักงานใหม่'))->get();
    //  $data2 = $data->whereHas('_id','615185df5d030000a9004e75');
    // $data::where('courses')->insert($request->course);   
    // ->update(['courses'=>$request->courses]);
    return response()->json([$data], 201);

    // return response()->json(["result" => "ok"], 201);  ok
  }

  public function setImage(Request $request, $id)
  {

    $image = User::find($id);
    if ($request->hasFile('image')) {
      $filename = $request->file('image')->getClientOriginalName();
      $filenameOnly = pathinfo($filename, PATHINFO_FILENAME);
      $externshion = $request->file('image')->getClientOriginalExtension();
      $comPic = str_replace('', '_', $filenameOnly) . '-' . rand() . '_' . time() . '.' . $externshion;
      $path = $request->file('image')->storeAs('public/image', $comPic);
      $image->image = $comPic;

      if ($image->save()) {
        $image::find($id)->update(['image' => $comPic,]);
        return ['status' => true, 'message' => 'Image Save Successfully'];
      } else {
        return ['status' => false, 'message' => 'Something error'];
      }
    }
  }
}
