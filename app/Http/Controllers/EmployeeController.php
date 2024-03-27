<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Traits\GeneralTrait;

class EmployeeController extends Controller
{
    use GeneralTrait;
    private $uploadPath = "assets/images/employees";

    public function createEmployee(RegisterEmployeeRequest $request)
    {
        try {
            DB::beginTransaction();

            $role=Role::find($request->role_id);
            if (!$role) {
                return $this->returnError("401",'Not found role');
            }
            $image=null;
            if (isset($request->image)) {
                $image = $this->saveImage($request->image, $this->uploadPath);
            }

            $data=User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => $request->password,
                'age'            => $request->age,
                'adress'         => $request->adress,
                'governorate'    => $request->governorate,
                'birth_date'     =>$request->birth_date,
                'image'          =>$image
            ]);
            $data->assignRole($role);
            DB::commit();
            return $this->returnData($data,'operation completed successfully');
        }
        catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(),'Please try again later');

        }
    }

    public function updateEmployee($id,RegisterEmployeeRequest $request)
    {
        try {
            DB::beginTransaction();
            $data=User::where('id',$id)->first();
            if (!$data) {
                return $this->returnError("401",'Not found');
            }
            $role=null;
            if (isset($request->role_id)){
                $role=Role::find($request->role_id);
                if (!$role) {
                    return $this->returnError("401",'Not found role');
                }
            }

            $data->update([
                'name'           => isset($request->name)? $request->name :$data->name,
                'email'          => isset($request->email)? $request->email :$data->email,
                'password'       => isset($request->password)? $request->password :$data->password,
                'age'            => isset($request->age)? $request->age :$data->age,
                'adress'         => isset($request->adress)? $request->adress :$data->adress,
                'governorate'    => isset($request->governorate)? $request->governorate :$data->governorate,
            ]);
            if($role){
                $data->syncRoles([$role]);
            }
            DB::commit();
            return $this->returnData($data,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollBack();
                return $this->returnError($ex->getCode(),'Please try again later');

        }
    }

    public function getById($id)
    {
        try {
            $data=User::where('id',$id)->first();
            if (!$data) {
                return $this->returnError("401",'Not found');
            }
            $data->loadMissing(['roles']);
            return $this->returnData($data,'operation completed successfully');
        } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(),'Please try again later');

        }
    }

    public function delete($id)
    {
        try {
            $data=User::where('id',$id)->first();
            if (!$data) {
                return $this->returnError("401",'Not found');
            }
            $data->delete();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(),'Please try again later');

        }
    }

}
