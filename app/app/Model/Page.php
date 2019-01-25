<?php

namespace App\Model;

use Carbon\Carbon;
use Cubitworx\Laravel\Model\Traits;
use Illuminate\Database\Eloquent\Model;

class Page extends Model {
	use Traits\ExtendedModel;

	protected $casts = [
		'options' => 'array',
	];

	protected $guarded = [
		'created_at',
		'updated_at',
	];

	public static function boot() {
		parent::boot();

		static::saving(function($model)  {
			$model->saving = $model->saving ?? ($model->title ?? '');
		});

		static::updating(function($model)  {
			$model->updating = $model->updating ?? ($model->title ?? '');
		});
	}

	public static function upsert(array $doc, array $where = null) {
		return parent::_upsert($doc, $where ?? ['url' => $doc['url']]);
	}

}
