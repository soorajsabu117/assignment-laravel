<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'product_name',
        'price',
        'sku',
        'description',
        'user_id',
        'created_at',
        'updated_at'
        ];
    protected $hidden = [
        'deleted_at'
    ];

    public function getCreatedAtAttribute($value){
        return date('Y-m-d H:i:s',strtotime($value));
    }
    public function getUpdatedAtAttribute($value){
        return date('Y-m-d H:i:s',strtotime($value));
    }
}
