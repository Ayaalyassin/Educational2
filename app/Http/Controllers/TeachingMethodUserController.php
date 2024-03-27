<?php

namespace App\Http\Controllers;

use App\Models\TeachingMethodUser;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TeachingMethodUserRequest;
use App\Models\TeachingMethod;

class TeachingMethodUserController extends Controller
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
    public function store(TeachingMethodUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user=auth()->user();

            $teaching_method=TeachingMethod::find($request->teaching_method_id);

            if(!$teaching_method)
                return $this->returnError("", 'teaching method not found');
            $teaching_methods_user= $user->teaching_methods_user()->attach([
                $request->teaching_method_id
            ]);
            $user->load(['teaching_methods_user']);

            DB::commit();
            return $this->returnData($teaching_method,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TeachingMethodUser $teachingMethodUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeachingMethodUser $teachingMethodUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeachingMethodUser $teachingMethodUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user=auth()->user();

            $teaching_method=TeachingMethod::find($id);

            if(!$teaching_method)
                return $this->returnError("", 'teaching method not found');

            $teaching_method_user=$user->teaching_methods_user()->where('teaching_method_id',$id)->first();
            if(!$teaching_method_user)
                return $this->returnError("", 'not found');
            $teaching_method_user->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
