<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Resultadopoker
 *
 * @property int $id
 * @property string $metodo
 * @property string $fo
 * @property float $fe
 * @property float $chi_cuadrado_calculado
 * @property float $chi_cuadrado_limite
 * @property int $grados_de_libertad
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
class Resultadopoker extends Model
{
	protected $table = 'resultadopoker';

	protected $casts = [
		'fe' => 'float',
		'chi_cuadrado_calculado' => 'float',
        'chi_cuadrado_limite' => 'float',
        'grados_de_libertad' => 'int',
		'resultado' => 'bool',
		'id_f' => 'int',
		'id_c' => 'int'
	];

	protected $fillable = [
		'metodo',
		'fo',
		'fe',
		'chi_cuadrado_calculado',
        'chi_cuadrado_limite',
        'grados_de_libertad',
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
