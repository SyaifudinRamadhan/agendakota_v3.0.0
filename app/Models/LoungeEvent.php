<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoungeEvent extends Model
{
	use HasFactory;

	protected $fillable = [
		'event_id', 'table_count', 'chair_table'
	];
}


 ?>