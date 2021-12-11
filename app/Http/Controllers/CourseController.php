<?php

namespace App\Http\Controllers;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Register;
use App\Models\User;

class CourseController extends Controller
{

    

    public function add(Request $request)
    {   
    
        Course::create([
            'course' => $request->course,
            'description' => $request->description,
            'speaker' => $request->speaker,
            'place' => $request->place,
            'hour' => $request->hour,
            'category' =>$request->category,
            'date' => $request->date,
            'time' => $request->time,
            'limited' => $request->limited,
            'registed' => 0,
        ]);
        
        return response()->json(["result" => "ok"], 201);
    }

    public function update(Request $request, $id)
    {   
        $post = Course::find($id);
        $post::find($id)->update([
            'course' => $request->course,
            'description' => $request->description,
            'speaker' => $request->speaker,
            'place' => $request->place,
            'hour' => $request->hour,
            'category' => $request->category,
            'date' => $request->date,
            'time' => $request->time,
            'limited' => $request->limited,
        ]);

        return response()->json(["result" => "ok"], 201);
    }


    public function show(Request $request)
    {
        // $course = Course::all();
        $course = Course::orderBy('created_at','desc')->get();
        return response()->json($course);
    }

    public function delete($data)
    {

        $post = Course::find($data);
        $post::find($data)->delete();

        return response()->json(["result" => "ok"], 200);
    }


    public function deletes(Request $request)
    {
        $data = $request->all();
        // echo print_r($data);
        foreach ($data as $key => $value) {
            $post = Course::find($value['_id']);
            $post->delete();
        }

        return response()->json(["result" => "ok"], 200);
    }

   

}
