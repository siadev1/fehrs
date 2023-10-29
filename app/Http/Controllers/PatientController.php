<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Patient;
use App\Models\Patient_next_of_kin;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PatientController extends Controller
{
    public function store(Request $request){
        $validate = $request->only([
            'firstname',
            'middlename',
            'lastname',
            'matric_no',
            'dob',
            'gender',
            'phone_no',
            'home_address',
            'email',
            "nok_firstname",
            "nok_middlename",
            "nok_lastname",
            "nok_relationship",
            "nok_dob",
            "nok_gender",
            "nok_phone_no"
            ]);

        $rule=[
            'firstname'=>['string','required'],
            'middlename'=>['string','required'],
            'lastname'=>['string','required'],
            'matric_no'=>['string','required','unique:patients'],
            'dob'=>['required'],
            'gender'=>['string','required'],
            'phone_no'=>['string','required','unique:patients'],
            'home_address'=>['string','required'],
            'email'=> ['string','required','unique:patients','email'],
            "nok_firstname"=> ['string','required'],
            "nok_middlename"=> ['string','required'],
            "nok_lastname"=> ['string','required'],
            "nok_relationship"=> ['string','required'],
            "nok_dob"=> ['required'],
            "nok_gender"=> ['string','required'],
            "nok_phone_no"=> ['string','required','unique:patient_next_of_kins']
        ];

        $message=[
            'firstname.required'=>'this field is required',
            'middlename.required'=>'this field is required',
            'lastname.required'=>'this field is required',
            'matric_no.required'=>'this field is required',
            'matric_no.unique:patients'=>'email used',
            'dob.required'=>'this field is required',
            'gender.required'=>'this field is required',
            'phone_no.integer'=>'must be an int',
            'home_address.required'=>'this field is required',
            'email.required'=>'this field is required'
            
        ];
        $custom=[
            // 'matric_no'=>'matric number already used',
            'nok_phone_no'=>"The next of kin number has been taken",
            // 'matric_no.unique:patients'=>'email used'
        ];
        $validator = validator::make($validate,$rule,$custom,$custom);
        // $validator->errors();
        // dd($validator->errors());
        // $validator->validated();
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }
        // if($validator->fails()){
        //     return withErrors($validate->errors())->withInput();
        // };
        // $validator->validate();



        $patient = new Patient();
        $patient->firstname= $request->firstname;
        $patient->middlename= $request->middlename;
        $patient->lastname= $request->lastname;
        $patient->matric_no= $request->matric_no;
        $patient->dob= $request->dob;
        $patient->gender= $request->gender;
        $patient->phone_no= $request->phone_no;
        $patient->home_address= $request->home_address;
        $patient->email= $request->email;
        $save=$patient->save();
        
        if($save){
            $patient_nok = new Patient_next_of_kin();
            $patient_nok->patient_id= $patient->id;
            $patient_nok->firstname= $request->nok_firstname;
            $patient_nok->middlename= $request->nok_middlename;
            $patient_nok->lastname= $request->nok_lastname;
            $patient_nok->relationship= $request->nok_relationship;
            $patient_nok->dob= $request->nok_dob;
            $patient_nok->gender= $request->gender;
            $patient_nok->nok_phone_no= $request->phone_no;
            $patient_nok->save();
            return response()->json(array('success'=>true,'id'=>$patient),200);
        }else{
            return response()->json(array('success'=>true,'id'=>$save),200);
            
            // $blog->email= $request->email;
        }

    }
    public function show(){
        $patient = Patient::with('prescriptions')->get(['id','firstname','middlename','lastname','phone_no','matric_no','email']);
        // $patients = Patient_next_of_kin::all();
        // $pat=$patient->patient_next_of_kin;
        // $pat_nok=$patient->patient_next_of_kin;
        return response()->json(array('patient_record'=>$patient),200);

    }

    public function update(Request $request,$id){


        $validate = $request->only([
            'firstname',
            'middlename',
            'lastname',
            'matric_no',
            'dob',
            'gender',
            'phone_no',
            'home_address',
            'email',
            "nok_firstname",
            "nok_middlename",
            "nok_lastname",
            "nok_relationship",
            "nok_dob",
            "nok_gender",
            "nok_phone_no"
        ]);

        $rule=[
            'firstname'=>['string','required'],
            'middlename'=>['string','required'],
            'lastname'=>['string','required'],
            'matric_no'=>['string','required','unique:patients'],
            'dob'=>['required'],
            'gender'=>['string','required'],
            'phone_no'=>['string','required','unique:patients'],
            'home_address'=>['string','required'],
            'email'=> ['string','required','unique:patients','email'],
            "nok_firstname"=> ['string','required'],
            "nok_middlename"=> ['string','required'],
            "nok_lastname"=> ['string','required'],
            "nok_relationship"=> ['string','required'],
            "nok_dob"=> ['required'],
            "nok_gender"=> ['string','required'],
            "nok_phone_no"=> ['string','required','unique:patient_next_of_kins']
        ];

        $message=[
            'firstname.required'=>'firstname field is required',
            'middlename.required'=>'middlename field is required',
            'lastname.required'=>'lastname field is required',
            'matric_no.required'=>'matric_nos field is required',
            'matric_no.unique:patients'=>'email used',
            'phone_no.unique:patients'=>'phone used',
            'dob.required'=>'dob field is required',
            'gender.required'=>'gender field is required',
            'phone_no.integer'=>'must be an int',
            'home_address.required'=>'home address field is required',
            'email.required'=>'email field is required'
        ];
        $custom=[
            // 'matric_no'=>'matric number already used'
            'nok_phone_no'=>"The next of kin number has been taken",
        ];
        $validator = validator::make($validate,$rule,$custom,$custom);
        // $validator->errors();
        // dd($validator->errors());
        // $validator->validated();
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }

        try {
            $patient= Patient::findOrFail($id);
        
            $input=$request->all();
    
                $patient->update($input);
                $save=$patient->update($input);
                if($save){
                    return response()->json(['message'=>$id]);
                }else{
                    echo 'i';
                };
        } catch (ModelNotFoundException $exception) {
            return response("patient with id {$id} not found");
        }


    }

    public function delete(Request $request,$id){


        

        try{
        $patient = Patient::findOrFail($id);
        $patient->delete();
       return response()->json("Delete successful");
        }catch (ModelNotFoundException $exception) {
            return response("patient with id {$id} not found");
        }
    }
    public function total(){
        $total= Patient::all()->count();
        return response()->json($total);
    }

    public function search(Request $request){

        $matric_no= $request->matric_no;
        $validate = $request->only([
            'matric_no',
        ]);

        $rule=[
            'matric_no'=>['string','required'],
        ];


        $custom=[
            
            'matric_no'=>"The matric number is not available",
        ];
        $validator = validator::make($validate,$rule,$custom);
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }

        try {

            $patient= Patient::where('matric_no',$matric_no)->get();
        
        } catch (ModelNotFoundException $exception) {
            return response("patient matric no not found");
        }
        
        if(!$patient->isEmpty()){

            return response()->json(["records"=>$patient]);
        }else{
            return response()->json(["records"=>0]);
        }

    }

}
