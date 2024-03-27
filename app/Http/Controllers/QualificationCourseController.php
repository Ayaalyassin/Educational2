<?php

namespace App\Http\Controllers;

use App\Models\QualificationCourse;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\QualificationCourseRequest;

class QualificationCourseController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $QualificationCourses=QualificationCourse::all();
            if (!$QualificationCourses) {
                return $this->returnError("401",'user Not found');
            }
            return $this->returnData($QualificationCourses,'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QualificationCourseRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $qualification_course= $user->qualification_courses()->create([
                'name' => $request->name,
                'description' =>$request->description,
                'date'=>$request->date
            ]);

            DB::commit();
            return $this->returnData($qualification_course,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            DB::beginTransaction();

            $QualificationCourse=QualificationCourse::find($id);
            if (!$QualificationCourse)
                return $this->returnError("401",'Not found');

            DB::commit();
            return $this->returnData($QualificationCourse,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualificationCourse $qualificationCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QualificationCourseRequest $request,$id)
    {

        try {
            DB::beginTransaction();
            $user=auth()->user();

            $qualification_course=$user->qualification_courses()->find($id);
            if(!$qualification_course)
                return $this->returnError("", 'not found');

            $qualification_course->update([
                'name' => isset($request->name)? $request->name :$qualification_course->name,
                'description' =>isset($request->description)? $request->description :$qualification_course->description,
                'date'=>isset($request->date)? $request->date :$qualification_course->date,
            ]);


            DB::commit();
            return $this->returnData($qualification_course,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user=auth()->user();
            $qualification_course=$user->qualification_courses()->find($id);
            if(!$qualification_course)
                return $this->returnError("", 'not found');
            $qualification_course->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
