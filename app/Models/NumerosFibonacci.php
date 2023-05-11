<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Numerosfibonacci
 * 
 * @property int $id
 * @property string $valoresBaseF
 * @property string $valoresGeneradosF
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $user_id
 * 
 * @property User|null $user
 * @property Collection|Resultadochi[] $resultadochis
 * @property Collection|Resultadopoker[] $resultadopokers
 *
 * @package App\Models
 */
class Numerosfibonacci extends Model
{
	protected $table = 'numerosfibonacci';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'valoresBaseF',
		'valoresGeneradosF',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function resultadochis()
	{
		return $this->hasMany(Resultadochi::class, 'id_f');
	}

	public function resultadopokers()
	{
		return $this->hasMany(Resultadopoker::class, 'id_f');
	}
}
