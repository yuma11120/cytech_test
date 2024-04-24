<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $fillable = ['company_name','street_address','representative_name'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
