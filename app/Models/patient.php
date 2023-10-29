<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class patient extends Model
{
    use HasFactory;
    protected $fillable =[

    "firstname",
    "middlename",
    "lastname",
    "matric_no",
    "dob",
    "gender",
    "phone_no",
    "home_address",
    "email",
    ];
    
    protected $hidden = [
        'updated_at',
        'created_at',
    ];


    public function patient_next_of_kin():HasOne{ 
        return $this->hasOne(Patient_next_of_kin::class,'patient_id');
    }
    public function prescriptions():HasMany{ 
        return $this->hasMany(prescription::class,'patient_id');
    }
    // public function record(): HasManyThrough
    // {
    //     return $this->hasManyThrough(drug_prescription::class, prescription::class);
    // }
    // public function doctors(): HasOne
    // {
    //     return $this->hasOne(User::class, 'doctor_id');
    // }
}
