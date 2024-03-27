<?php

namespace App\Http\Controllers;

use App\Models\AppointmentAvailable;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AppointmentAvailableRequest;
use App\Models\ServiceTeacher;
use App\Models\User;
use Carbon\Carbon;

class AppointmentAvailableController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($teacher_id)
    {
        try {
            DB::beginTransaction();

            $user=User::find($teacher_id);
            if(!$user)
                return $this->returnError("", 'Teacher not found');

            $startOfWeek=Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
            $endOfWeek=Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

            $appointment_available= $user->appointment_available()->get();//->whereBetween('start_date',[$startOfWeek,$endOfWeek])->get();

            DB::commit();
            return $this->returnData($appointment_available,'operation completed successfully');
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
    public function store(AppointmentAvailableRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $ServiceTeacher=ServiceTeacher::find($request->service_teacher_id);
            if(!$ServiceTeacher)
                return $this->returnError("", 'Service Teacher not found');

            $appointment_available= $user->appointment_available()->create([
                'start_date' => $request->start_date,
                'end_date'=>$request->end_date,
                'status'=>"status",
                'is_valid'=>1,
                'service_teacher_id'=>$request->service_teacher_id,
            ]);

            $appointment_available->loadMissing(['service_teacher']);

            DB::commit();
            return $this->returnData($appointment_available,'operation completed successfully');
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

            $AppointmentAvailable=AppointmentAvailable::find($id);
            if(!$AppointmentAvailable)
                return $this->returnError("", 'Appointment Available not found');
            DB::commit();
            return $this->returnData($AppointmentAvailable,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppointmentAvailable $appointmentAvailable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, AppointmentAvailableRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $AppointmentAvailable=$user->appointment_available()->find($id);
            if(!$AppointmentAvailable)
                return $this->returnError("", 'Appointment Available not found');

            $ServiceTeacher=ServiceTeacher::find($request->service_teacher_id);
            if(!$ServiceTeacher)
                return $this->returnError("", 'Service Teacher not found');

            $appointment_available= $user->appointment_available()->update([
                'start_date' => isset($request->start_date)? $request->start_date :$AppointmentAvailable->start_date,
                'end_date'=>isset($request->end_date)? $request->end_date :$AppointmentAvailable->end_date,
                'status'=>"status",
                'service_teacher_id'=>isset($request->service_teacher_id)? $request->service_teacher_id :$AppointmentAvailable->service_teacher_id,
            ]);

            $appointment_available->loadMissing(['service_teacher']);

            DB::commit();
            return $this->returnData($appointment_available,'operation completed successfully');
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

            $appointment_available= $user->appointment_available()->find($id);
            if(!$appointment_available)
                return $this->returnError("", 'not found');
            $appointment_available->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
