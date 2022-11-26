<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Casts\Attribute;
class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','slug','image'
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }
    
    protected function image(): Attribute{
        return Attribute::make(
            get: fn ($value) => asset('/storage/categories/' . $value),
        );
    }
}
