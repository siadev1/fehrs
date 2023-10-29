<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Prescription;
use App\Models\Drug_in;
use App\Models\Drug_out;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\User;

class prescriptionController extends Controller
{
    public function store(Request $request){
        $validate = $request->only
        ([
            'patient_id',
            // 'user_id',
            'diagnosis',
            'comment',
            'drug_quantity',
            'drug_id'

            
        ]);

        $rule=[
            'patient_id'=>['int','required'],
            // 'user_id'=>['string','required'],
            'diagnosis'=>['string','required'],
            'comment'=>['string','required'],
            'drug_quantity'=>['array','required'],
            'drug_id'=>['array','required']

        ];

        $message=[
            'drug_name.required'=>'must be a string',
        ];
        $custom=[
            'matric_no'=>'matric number already used'
        ];
        $validator = validator::make($validate,$rule,$message,$custom);
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['errors' => $errors]);
        }
        // $input= $request->all();
        $prescription = $request->user()->prescriptions()->create([
            "patient_id"=> $request->patient_id,
            // "user_id"= $request->user_id,
            "diagnosis"=> $request->diagnosis,
            "comment"=>$request->comment,
            "drug_quantity"=> implode(" ",$request->drug_quantity),

        ]);
        // $prescription= new Prescription;
        // $prescription->patient_id= $request->patient_id;
        // $prescription->user_id= $request->user_id;
        // $prescription->diagnosis= $request->diagnosis;
        // $prescription->comment=$request->comment;
        // $prescription->drug_quantity= implode(" ",$request->drug_quantity);
        
        // $prescription->prescription= implode(" ",$request->prescription);
        $prescription->save();
        if($prescription->save()){
            $prescription->drugs()->sync($request->drug_id);
            return response()->json(["success","details"=>$prescription]);
        }else{
            return response()->json("fail");
        }

    }

    public function update(Request $request,$id){
        $prescription= Prescription::findOrFail($id);
        $q=explode(" ",$prescription->drug_quantity);
        $quantity=$request->drug_quantity;
        $i=0;
        foreach($prescription->drugs as $a){
            // keep record of quantity of drug dispensed
            $drugOut= new drug_out;
            $drugOut->drug_id = $a['id'];
            $drugOut->quantity = $q[$i];
            $drugOut->save();
            // update drugs in the pharmacy
            $drugUpdate = Drug::findOrFail($a['id']);
            $drugUpdate->drug_quantity -=$q[$i];
            $drugUpdate->update();
            $i++;
        }
        $prescription->status=1;
        $check=$prescription->update();
        if($check){

            return response()->json("success");
        }
    }

    public function show(){
        $prescription = Prescription::where('status',0)->latest()->with(['drugs:id,drug_name','patient:id,matric_no','user:id,name'])->get();
        
        return response()->json(["records"=>$prescription]);

    }

    public function total(){
        $total= Prescription::all()->count();
        return response()->json($total);
    }

    public function get(Request $request){
        $id = $request->user()->id;
        $prescription = Prescription::where('user_id',$id)->latest()->with(['drugs:id,drug_name','patient:id,matric_no'])->get();
        
        return response()->json(["records"=>$prescription]);

    }

    public function records(Request $request){
        $prescription = Prescription::where('status',0)->latest()->with(['drugs:id,drug_name','patient:id,matric_no','user:id,name'])->get();
        
        return response()->json(["records"=>$prescription]);

    }
}
