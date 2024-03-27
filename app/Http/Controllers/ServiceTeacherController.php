<?php

namespace App\Http\Controllers;

use App\Models\ServiceTeacher;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ServiceTeacherRequest;
use App\Models\User;

class ServiceTeacherController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($teacher_id)
    {
        try {
            $user=User::find($teacher_id);
            if (!$user) {
                return $this->returnError("401",'user Not found');
            }
            $service_teachers=$user->service_teachers()->get();
            return $this->returnData($service_teachers,'operation completed successfully');
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
    public function store(ServiceTeacherRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $service_teacher= $user->service_teachers()->create([
                'price' => $request->price,
                'type' =>$request->type,
            ]);


            DB::commit();
            return $this->returnData($service_teacher,'operation completed successfully');
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

            $ServiceTeacher= ServiceTeacher::find($id);
            if (!$ServiceTeacher) {
                return $this->returnError("401",'ServiceTeacher Not found');
            }

            DB::commit();
            return $this->returnData($ServiceTeacher,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceTeacher $serviceTeacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceTeacherRequest $request,$id)
    {
        try {
            DB::beginTransaction();
            $user=auth()->user();

            $service_teacher=$user->service_teachers()->find($id);

            if(!$service_teacher)
                return $this->returnError("", 'not found');

            $service_teacher->update([
                'price' => isset($request->price)? $request->price :$service_teacher->price,
                'type' =>isset($request->type)? $request->title :$service_teacher->type,
            ]);


            DB::commit();
            return $this->returnData($service_teacher,'operation completed successfully');
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
            $service_teacher=$user->service_teachers()->find($id);
            if(!$service_teacher)
                return $this->returnError("", 'not found');
            $service_teacher->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
