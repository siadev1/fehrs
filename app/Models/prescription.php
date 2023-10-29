<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Database\Eloquent\Relations\HasMany;

class prescription extends Model
{
    use HasFactory;


     protected $fillable = [
        'patient_id',
        'user_id',
        'diagnosis',
        'comment',
        'drug_quantity',
        'status',
    ]; 

    protected $hidden = [
        'updated_at',
        'created_at',
        'status'
    ];
    
    /**
     * Get the user that owns the prescription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
    public function drugs(){
        return $this->belongsToMany(Drug::Class)->withTimestamps();
    }
}
