<?php
namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Hash;
class UsersController 
{	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $_request;
	public function __construct(User $user,Request $request){
        $this->_request = $request;
        $this->_model = $user;
    }
    public function insert() {
        try{   
            if(isset($this->_request->name) && !is_null($this->_request->name) && $this->_request->name!='' 
                && isset($this->_request->email) && !is_null($this->_request->email) && $this->_request->email!='' 
                && isset($this->_request->role) && !is_null($this->_request->role) && $this->_request->role > 0
                && isset($this->_request->phone) && !is_null($this->_request->phone) && $this->_request->phone!=''){
                
                if(isset($this->_request->old_email)){
                    $oldEmail = $this->_request->old_email;
                    $email = $this->_request->email;
                    $userDtl = $this->_model->where(function($query) use ($oldEmail, $email){
                                $query->where('email', $email)
                                    ->orWhere('email',$oldEmail);
                                })->first();
                }
                else
                    $userDtl = $this->_model->where('email',$this->_request->email)->first();
                
                $params = array(
                    'name'      => $this->_request->name,
                    'email'     => $this->_request->email, 
                    'phone'     => $this->_request->phone,                 
                );
                if(empty($userDtl)){
                    if(isset($this->_request->password) && !is_null($this->_request->password))
                        $params['password'] = $this->_request->password;
                        
                    // if(isset($this->_request->status) && !is_null($this->_request->status))
                    //     $params['is_active'] = $this->_request->status;
                    $params['is_active'] = $this->_request->is_active;

                    $user= $this->_model::create($params);
                    if(!empty($user)){
                        $role = Role::find($this->_request->role);
                        $user->roles()->attach($role);
                        return response()->json(["status"=> true, "message"=> 'Created Successfully'], 200);
                    }
                    else
                        return response()->json(["status"=> false, "error"=>'Not Inserted'], 422);
                }
                else
                {
                    if(isset($this->_request->password) && !is_null($this->_request->password))
                        $params['password'] = $this->_request->password;
                    if(isset($this->_request->status) && !is_null($this->_request->status))
                        $params['is_active'] = $this->_request->status;
                    $update = $this->_model->where('email',$this->_request->old_email)->update($params);
                    if($update)
                        return response()->json(["status"=> true, "message"=> 'Updated Successfully'], 200);
                    else
                        return response()->json(["status"=> true, "message"=> 'Nothing To Update'], 200);
                }
            }
            else
                return response()->json(["status"=> false, "error"=>'Provide all parameters'], 422);
            
        }
        catch (\Exception $e){
            return response()->json(["status"=> false, "error"=>'Internal Server Error'], 500);
        }
    }
    public function updatePassword(){
        try{   
            if(isset($this->_request->password) && !is_null($this->_request->password) && $this->_request->password!='' && isset($this->_request->email) && !is_null($this->_request->email) && $this->_request->email!=''){
                $userDtl = $this->_model->where(['email'=>$this->_request->email])->first();
                
                if(!empty($userDtl)){
                    $update= $userDtl->update(['password'=>$this->_request->password]);
                    if($update)
                        return response()->json(["status"=> true, "message"=> 'Updated Successfully'], 200);
                    else
                        return response()->json(["status"=> false, "error"=>'Not Updated'], 422);
                }
                else
                    return response()->json(["status"=> false, "error"=>'Email doesnot exists'], 422);
            }
            else
                return response()->json(["status"=> false, "error"=>'Provide Password'], 422);
            
        }
        catch (\Exception $e){
            return response()->json(["status"=> false, "error"=>'Internal Server Error'], 500);
        }
    }
    public function delete(){
        try{   
            if(isset($this->_request->email) && !is_null($this->_request->email) && $this->_request->email!='' && isset($this->_request->role) && !is_null($this->_request->role) && $this->_request->role>0){
                $user = $this->_model->select('id','email')->where(['email'=>$this->_request->email])->first();
                $role = Role::find($this->_request->role);
                $user->roles()->detach($role);
                
                if(!empty($user)){
                    $delete= $user->delete();
                    if($delete)
                        return response()->json(["status"=> true, "message"=> 'Deleted Successfully'], 200);
                    else
                        return response()->json(["status"=> false, "error"=>'Not Deleted'], 422);
                }
                else
                    return response()->json(["status"=> false, "error"=>'Email doesnot exists'], 422);
            }
            else
                return response()->json(["status"=> false, "error"=>'Provide all parameter'], 422);
            
        }
        catch (\Exception $e){
            return response()->json(["status"=> false, "error"=>'Internal Server Error'], 500);
        }
    }
}