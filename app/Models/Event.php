<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class Event extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array

     */
    protected $fillable = [
        'id', 'title', 'description', 'start', 'end', 'color', 'penyelenggara', 'penyelenggara_unit', 'penanggung_jawab', 'contact_person', 'email_pj'
    ];
}