<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileStudentRequest;
use App\Models\ProfileStudent;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class ProfileStudentController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(ProfileStudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $profile_student= $user->profile_student()->create([
                'educational_level' => isset($request->educational_level) ? $request->educational_level : null,
                'description' => isset($request->description) ? $request->description : null,
            ]);


            DB::commit();
            return $this->returnData($profile_student,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $profile_student= $user->profile_student()->first();

            DB::commit();
            return $this->returnData($profile_student,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }



    public function getById($id)
    {
        try {
            DB::beginTransaction();

            $profile_student=ProfileStudent::find($id);
            if (!$profile_student)
                return $this->returnError("401",'Not found');

            DB::commit();
            return $this->returnData($profile_student,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileStudent $profileStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileStudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $profile_student = $user->profile_student()->first();

            $profile_student->update([
                'educational_level' => isset($request->educational_level)? $request->educational_level :$profile_student->educational_level,
                'description' => isset($request->description)? $request->description :$profile_student->description,
            ]);


            DB::commit();
            return $this->returnData($profile_student,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $profile_student= $user->profile_student()->first();
            if(!$profile_student)
                return $this->returnError("", 'not found');
            $profile_student->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
