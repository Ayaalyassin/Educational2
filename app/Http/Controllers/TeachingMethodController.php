<?php

namespace App\Http\Controllers;

use App\Models\TeachingMethod;
use Illuminate\Http\Request;
use App\Http\Requests\TeachingMethodRequest;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class TeachingMethodController extends Controller
{
    use GeneralTrait;

    private $uploadPath = "assets/images/teaching_methods";
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
            $teaching_methods=$user->teaching_methods()->get();
            return $this->returnData($teaching_methods,'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
    {
        try {
            DB::beginTransaction();

            $teaching_method= TeachingMethod::find($id);
            if (!$teaching_method) {
                return $this->returnError("401",'teaching_method Not found');
            }

            DB::commit();
            return $this->returnData($teaching_method,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function store(TeachingMethodRequest $request)
    {
        try {
            DB::beginTransaction();
            $user=auth()->user();

            $file=null;
            if (isset($request->file)) {
                $file = $this->saveImage($request->file, $this->uploadPath);
            }

            $teaching_method= $user->teaching_methods()->create([
                'title'=>$request->title,
                'type'=>$request->type,
                'description'=>$request->description,
                'file'=>$file,
                'status'=>0
            ]);


            DB::commit();
            return $this->returnData($teaching_method,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeachingMethod $teachingMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeachingMethodRequest $request,$id)
    {
        try {
            DB::beginTransaction();
            $user=auth()->user();

            $teaching_method=$user->teaching_methods()->find($id);

            $file=null;
            if (isset($request->file)) {
                $file = $this->saveImage($request->file, $this->uploadPath);
            }

            $teaching_method->update([
                'title'=>isset($request->title)? $request->title :$teaching_method->title,
                'type'=>isset($request->type)? $request->type :$teaching_method->type,
                'description'=>isset($request->description)? $request->description :$teaching_method->description,
                'file'=>isset($request->file)? $file :$teaching_method->file,
            ]);

            DB::commit();
            return $this->returnData($teaching_method,'operation completed successfully');
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
            $teaching_method=$user->teaching_methods()->find($id);

            if (!$teaching_method) {
                return $this->returnError("401",'teaching_method Not found');
            }

            $teaching_method->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
