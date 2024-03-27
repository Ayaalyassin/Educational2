<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use App\Http\Requests\AdsRequest;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class AdsController extends Controller
{
    use GeneralTrait;

    private $uploadPath = "assets/images/ads";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ads=Ads::all();
            return $this->returnData($ads,'operation completed successfully');
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
    public function store(AdsRequest $request)
    {
        try {
            DB::beginTransaction();
            $user=auth()->user();

            $file=null;
            if (isset($request->file)) {
                $file = $this->saveImage($request->file, $this->uploadPath);
            }

            $ads= $user->ads()->create([
                'name'=>$request->name,
                'description'=>$request->description,
                'price'=>$request->price,
                'number_students'=>$request->number_students,
                'file'=>$file,
            ]);


            DB::commit();
            return $this->returnData($ads,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Display the specified resource.
     */

     public function getById($id)
     {
        try {
            $data=Ads::where('id',$id)->first();
            if (!$data) {
                return $this->returnError("401",'Not found');
            }
            return $this->returnData($data,'operation completed successfully');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),'Please try again later');

        }
     }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ads $ads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdsRequest $request,$id)
    {
        try {
            DB::beginTransaction();
            $user=auth()->user();

            $ads=$user->ads()->find($id);
            if(!$ads)
                return $this->returnError("", 'not found');

            $file=null;
            if (isset($request->file)) {
                $file = $this->saveImage($request->file, $this->uploadPath);
            }
            $ads->update([
                'name'=>isset($request->name)? $request->name :$ads->name,
                'description'=>isset($request->description)? $request->description :$ads->description,
                'price'=>isset($request->price)? $request->price :$ads->price,
                'number_students'=>isset($request->number_students)? $request->number_students :$ads->number_students,
                'file'=>isset($request->file)? $file :$ads->file,
            ]);

            DB::commit();
            return $this->returnData($ads,'operation completed successfully');
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

            $ads=$user->ads()->find($id);
            if(!$ads)
                return $this->returnError("", 'not found');
            if (isset($ads->file)) {
                $this->deleteImage($ads->file);
            }

            $ads->delete();
            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
