<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Contact;
use App\Models\Addresse;
use Illuminate\Support\Facades\DB;
use App\Models\Depart;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          $data = DB::select("SELECT e.*,GROUP_CONCAT(DISTINCT (CONCAT(c.id,' ; ',c.number))) AS mobile_num ,GROUP_CONCAT(DISTINCT (CONCAT(a.id,' ; ',a.address))) AS emp_address FROM `employees` AS e  LEFT JOIN 
                contacts AS c ON e.id = c.emp_id 
                LEFT JOIN `addresses` AS a ON c.emp_id = a.emp_id 
            GROUP BY id ,c.emp_id,a.emp_id");
          return $data;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = Depart::find($request->dept_id);
        
        if($department == ''){
            return 'Department does not exist';    
        }
        $employee = new Employee();
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->gender = $request->gender;
        $employee->dept_id = $request->dept_id;
        $employee->save();   
        foreach ($request->mobile as $key => $number) {
            $mobile = new Contact();
            $mobile->number = $number;
            $mobile->emp_id = $employee->id;
            $mobile->save();  
        }  
        foreach ($request->address as $key => $address_data) {
            $address = new Addresse();
            $address->address = $address_data;
            $address->emp_id = $employee->id;
            $address->save();  
        }  
         if($employee->id != ''){
            return array("status"=>"success","dep_id"=>$employee->id);
        }else{
            return array("status"=>"false");  
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->gender = $request->gender;
        $employee->dept_id = $request->dept_id;
        $employee->save();   
        foreach ($request->mobile as $key => $number) {
            
            if(isset($number['id'])){
                $mobile_update = Contact::find($number['id']);
                $mobile_update->number = $number;
                $mobile_update->save();  
            }
        }  
        foreach ($request->address as $key => $address_data) {
            
            if(isset($address_data['id'])){
                $address_update = Addresse::find($address_data['id']);
                $address_update->address = $address_data['value'];
                $address_update->save();  
            }   
        }  
         return 'updated';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = Employee::destroy($id);
        Contact::where('emp_id',$id)->delete();
        Addresse::where('emp_id',$id)->delete();
        return $deleted ? 'deleted' : 'error';
    }
}
