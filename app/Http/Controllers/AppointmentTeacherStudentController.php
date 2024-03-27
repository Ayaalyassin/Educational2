<?php

namespace App\Http\Controllers;

use App\Models\AppointmentTeacherStudent;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Http\Requests\AppointmentTeacherStudentRequest;
use App\Models\AppointmentAvailable;
use Illuminate\Support\Facades\DB;

class AppointmentTeacherStudentController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     */
    public function getStudentAppointment()
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $appointment_student= $user->appointment_student_teacher()->get();

            $appointment_student->loadMissing(['appointment_available']);

            DB::commit();
            return $this->returnData($appointment_student,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }


    public function getTeacherAppointmentAvailable($teacher_id)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $appointment_teacher= $user->appointment_available()->where('user_id',$teacher_id)
            ->where('is_valid',1)->get();


            DB::commit();
            return $this->returnData($appointment_teacher,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
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
    public function store(AppointmentTeacherStudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $appointment_available=AppointmentAvailable::where('id',$request->appointment_available_id)
                                   ->where('is_valid',1)->first();
            if(!$appointment_available)
                return $this->returnError("", 'appointment available not found');

            $teacher=User::find($request->teacher_id);
            if(!$teacher)
                return $this->returnError("", 'Teacher not found');

            $appointment_student_teacher= $user->appointment_student_teacher()->create([
                'type_lesson' => $request->type_lesson,
                'teacher_id'=>$request->teacher_id,
                "status"=>"status",
                'appointment_available_id'=>$request->appointment_available_id,
            ]);

            $appointment_available->is_valid=0;
            $appointment_available->save();

            $appointment_student_teacher->loadMissing(['appointment_available']);

            DB::commit();
            return $this->returnData($appointment_student_teacher,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $appointment_student= $user->appointment_student_teacher()->find($id);

            $appointment_student->loadMissing(['appointment_available']);

            DB::commit();
            return $this->returnData($appointment_student,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppointmentTeacherStudent $appointmentTeacherStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppointmentTeacherStudent $appointmentTeacherStudent)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $appointment_student_teacher= $user->appointment_student_teacher()->find($id);
            if(!$appointment_student_teacher)
                return $this->returnError("", 'not found');
            $appointment_student_teacher->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
