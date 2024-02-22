<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Facades
use Validator;
use Carbon;

// Model
use App\Models\Student;

/**
 * @OA\Server(
 *      url="http://localhost/PROJECTS/API/vueCRUDApiSwagger/public/",
 *      description="Local Server"
 * )
 */

class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/students",
     *     tags={"students"},
     *     summary="Get all students",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Per page count",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="10",
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */

    public function index(){
        $students = Student::all();
        if($students->count() > 0){
            return response()->json(['status' => 200, 'students' => $students], 200);
        }else{
            return response()->json(['status' => 404, 'message' => 'No Records Found'], 404);
        }
    }

    /**
     * Add a new record to the store.
     *
     * @OA\Post(
     *     path="/api/students",
     *     tags={"students"},
     *     operationId="store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Record Add",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="course", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string", format="phone"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     )
     * )
     */

    public function store(Request $request){
        $nowTime    = Carbon::now();
        $validator  = Validator::make($request->all(), [
            'name'      => 'required|string|max:191',
            'course'    => 'required|string|max:191',
            'email'     => 'required|email|max:191',
            'phone'     => 'required|digits:10',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }else{
            $student= Student::create([
                'name'          => $request->name,
                'course'        => $request->course,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'created_at'    => $nowTime,
            ]);

            if($student){
                return response()->json(['status' => 200, 'message'=> 'Record has Created Successfully'], 200);
            }else{
                return response()->json(['status' => 500, 'message'=> 'Something Went Wrong'], 500);
            }
        }
    }

    /**
     * Display particular record.
     *
     * @OA\Get(
     *     path="/api/students/{id}",
     *     tags={"students"},
     *     description="Display a particular record",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the record that needs to be fetched",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1,
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful Operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="student", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="course", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="phone", type="string", format="phone"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found",
     *     )
     * )
     */

    public function show($id){
        $student = Student::find($id);
        if($student){
            return response()->json(['status' => 200, 'student' => $student], 200);
        }else{
            return response()->json(['status' => 404, 'message' => 'No Such Record Found!'], 404);
        }
    }

    /**
     * Display particular record to edit.
     *
     * @OA\Get(
     *     path="/api/students/{id}/edit",
     *     tags={"students"},
     *     description="Display a particular record to edit",
     *     operationId="edit",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the record that needs to be fetched for edit",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1,
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful Record Operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="student", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="course", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="phone", type="string", format="phone"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found",
     *     )
     * )
     */

    public function edit($id){
        $student = Student::find($id);
        if($student){
            return response()->json(['status' => 200, 'student' => $student], 200);
        }else{
            return response()->json(['status' => 404, 'message' => 'No Such Record Found!'], 404);
        } 
    }

    /**
     * Update an existing student record.
     *
     * @OA\Put(
     *     path="/api/students/{id}/edit",
     *     tags={"students"},
     *     operationId="update",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the student record to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1,
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Record has been updated successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="No such record found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation exception",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=422),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Record data to update",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="course", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string", format="phone"),
     *         )
     *     )
     * )
     */

    public function update(Request $request, int $id){
        $nowTime    = Carbon::now();
        $validator  = Validator::make($request->all(), [
            'name'      => 'required|string|max:191',
            'course'    => 'required|string|max:191',
            'email'     => 'required|email|max:191',
            'phone'     => 'required|digits:10',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }else{
            $student = Student::find($id);
            if($student){
                $student->update([
                    'name'          => $request->name,
                    'course'        => $request->course,
                    'email'         => $request->email,
                    'phone'         => $request->phone,
                    'updated_at'    => $nowTime,
                ]);
                return response()->json(['status' => 200, 'message' => 'Record has Updated Successfully'], 200);
            }else{
                return response()->json(['status' => 404, 'message' => 'No Such Record Found!'], 404);
            }
        }
    }
}
