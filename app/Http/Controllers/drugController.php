<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Drug;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Drug_in;

class drugController extends Controller
{
    public function store(Request $request){
        $validate = $request->all();

        $rule=[
            'drug_name'=>['string','required'],
            'drug_quantity'=>['string','required'],
            'brand_name'=>['string','required'],
            'package_size'=>['string','required'],
            'manufacturer'=>['string','required'],
            'batch_no'=>['string','required'],
            'manufacturing_date'=>['string','required'],
            'expiring_date'=>['string','required'],
            'nafdac_number'=>['string','required','unique:drugs'],
            'dosage_form'=>['string','required'],
            'concentration'=>['string','required'],
            'drug_description'=>['string','required'],

        ];

        $message=[
            'drug_name.required'=>'drug name is required',
            'drug_quantity.required'=>'drug_quantity is required'  ,
            'brand_name.required'=>  'brand_name is required',
            'package_size.required'=>'package_size is required',
            'manufacturer.required'=>'manufacturer is required',
            'batch_no.required'=>'batch_no is required',
            'manufacturing_date.required'=>'manufacturing_date is required',
            'expiring_date.required'=>'expiring_date is required',
            'nafadc_number.required'=>'quantity is required',
            'dosage_form.required'=>'quantity is required',
            'concentration.required'=>'quantity is required',
            'drug_description.required'=>'quantity is required',
        ];
        $custom=[
            'matric_no'=>'matric number already used'
        ];
        $validator = validator::make($validate,$rule,$message,$custom);
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }
        $input = $request->all();
        // $drug =Drug::create($input);
        $drug =new Drug;
        $drug->drug_name= $request->drug_name;
        $drug->drug_quantity= $request->drug_quantity;
        $drug->brand_name= $request->brand_name;
        $drug->package_size= $request->package_size;
        $drug->manufacturer= $request->manufacturer;
        $drug->batch_no= $request->batch_no;
        $drug->manufacturing_date= $request->manufacturing_date;
        $drug->expiration_date= $request->expiring_date;
        $drug->nafdac_number= $request->nafdac_number;
        $drug->dosage_form= $request->dosage_form;
        $drug->concentration= $request->concentration;
        $drug->drug_description= $request->drug_description;
        $save=$drug->save();
        
        if($save){
            $drug_in= new drug_in;
            $drug_in->drug_id= $drug->id;
            $drug_in->quantity = $request->drug_quantity;
            $drug_in->save();
            return response()->json(array('success'=>true,'id'=>$drug),200);
        }else{
            return response()->json(array('success'=>false));
            
            
        }

    }
    public function drug_in(Request $request){
        $validate = $request->only([
            'drug_id',
            'quantity',
            
        ]);

        $rule=[
            'drug_id'=>['string','required'],
            'quantity'=>['string','required'],
        ];

        $message=[
            'drug_id.required'=>'must be a string',
            'quantity.required'=>'must be a string',
            
        ];
        $custom=[
            'matric_no'=>'matric number already used',
            'nafdac_number'=>'nafdac cant be the same'
        ];
        $validator = validator::make($validate,$rule,$message,$custom);
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }
        // $drug_in= new Drug_in();
        // // $drug_in =$request->all();
        // $drug_in->drug_id= $request->drug_id;
        // $drug_in->quantity= $request->quantity;
        // $drug_in->save();

        $drug = Drug::findOrFail(1);
        $drug->quantity += $request->quantity;
        $save=$drug->update();
        return response()->json(array('success'=>false, 'details'=>$drug));



    }

    public function drug_out(Request $request){
        $validate = $request->only([
            'drug_id',
            'quantity',
            
        ]);

        $rule=[
            'drug_id'=>['string','required'],
            'quantity'=>['string','required'],
        ];

        $message=[
            'drug_id.required'=>'must be a string',
            'quantity.required'=>'must be a string',
        ];
        $custom=[
            'matric_no'=>'matric number already used'
        ];
        $validator = validator::make($validate,$rule,$message,$custom);
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }
        $drug_in= new Drug_in();
        // $drug_in =$request->all();
        $drug_in->drug_id= $request->drug_id;
        $drug_in->quantity= $request->quantity;
        $drug_in->save();

        $drug = Drug::findOrFail(1);
        $drug->quantity -= $request->quantity;
        $save=$drug->update();
        return response()->json(array('success'=>false, 'details'=>$drug));



    }
    public function show(){
        $drug= Drug::get(['id','drug_name','drug_quantity','manufacturing_date','expiration_date']);
        return response()->json(array( 'details'=>$drug));

    }

    public function update(Request $request,$id){


        $validate = $request->all();

        $rule=[
            'drug_name'=>['string','required'],
            'drug_quantity'=>['string','required'],
            'brand_name'=>['string','required'],
            'package_size'=>['string','required'],
            'manufacturer'=>['string','required'],
            'batch_no'=>['string','required'],
            'manufacturing_date'=>['string','required'],
            'expiring_date'=>['string','required'],
            'nafdac_number'=>['string','required','unique:drugs'],
            'dosage_form'=>['string','required'],
            'concentration'=>['string','required'],
            'drug_description'=>['string','required'],

        ];

        $message=[
            'drug_name.required'=>'drug name is required',
            'drug_quantity.required'=>'drug_quantity is required'  ,
            'brand_name.required'=>  'brand_name is required',
            'package_size.required'=>'package_size is required',
            'manufacturer.required'=>'manufacturer is required',
            'batch_no.required'=>'batch_no is required',
            'manufacturing_date.required'=>'manufacturing_date is required',
            'expiring_date.required'=>'expiring_date is required',
            'nafadc_number.required'=>'quantity is required',
            'dosage_form.required'=>'quantity is required',
            'concentration.required'=>'quantity is required',
            'drug_description.required'=>'quantity is required',
        ];
        $custom=[
            'matric_no'=>'matric number already used'
        ];
        $validator = validator::make($validate,$rule,$message,$custom);
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }
        
        try {
            $drug= Drug::findOrFail($id);
        
            
            $drug->drug_name= $request->drug_name;
            $drug->drug_quantity= $request->drug_quantity;
            $drug->brand_name= $request->brand_name;
            $drug->package_size= $request->package_size;
            $drug->manufacturer= $request->manufacturer;
            $drug->batch_no= $request->batch_no;
            $drug->manufacturing_date= $request->manufacturing_date;
            $drug->expiration_date= $request->expiring_date;
            $drug->nafdac_number= $request->nafdac_number;
            $drug->dosage_form= $request->dosage_form;
            $drug->concentration= $request->concentration;
            $drug->drug_description= $request->drug_description;    
            $save=$drug->update();
            if($save){
                return response()->json(['message'=>$id]);
            }else{
                echo 'i';
            };
        } catch (ModelNotFoundException $exception) {
            return response("Drug with id {$id} not found");
        }


    }

    public function delete(Request $request,$id){

        try{
        $Drug = Drug::findOrFail($id);
        $Drug->delete();
       return response()->json("Delete successful");
        }catch (ModelNotFoundException $exception) {
            return response("Drug with id {$id} not found");
        }
    }

}



