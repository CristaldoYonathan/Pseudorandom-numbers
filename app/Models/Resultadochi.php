<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Resultadochi
 * 
 * @property int $id
 * @property string $metodo
 * @property int $fo
 * @property float $fe
 * @property float $chi_cuadrado_calculado
 * @property bool $resultado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $id_f
 * @property int $id_c
 * 
 * @property Numeroscongruencium $numeroscongruencium
 * @property Numerosfibonacci $numerosfibonacci
 *
 * @package App\Models
 */
class Resultadochi extends Model
{
	protected $table = 'resultadochi';

	protected $casts = [
		'fo' => 'int',
		'fe' => 'float',
		'chi_cuadrado_calculado' => 'float',
		'resultado' => 'bool',
		'id_f' => 'int',
		'id_c' => 'int'
	];

	protected $fillable = [
		'metodo',
		'fo',
		'fe',
		'chi_cuadrado_calculado',
		'resultado',
		'id_f',
		'id_c'
	];

	public function numeroscongruencium()
	{
		return $this->belongsTo(Numeroscongruencium::class, 'id_c');
	}

	public function numerosfibonacci()
	{
		return $this->belongsTo(Numerosfibonacci::class, 'id_f');
	}
}
