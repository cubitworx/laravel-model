<?php

namespace Cubitworx\Laravel\Model\Traits;

use Carbon\Carbon;

trait ExtendedModel {

	public static function defaults(array $doc) {
		return [];
	}

	/**
	 * Apply DB mutation defaults and batch insert records
	 *
	 * Chunk insert for SQLlite which is currently limited to bulk insert limit of 1000 records
	 */
	public static function insertWithDefaults(array $data, array $defaults = []) {
		$defaults += [
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		];

		$model = new static();

		foreach ($data as &$doc) {
			if (!empty($model->casts)) {
				foreach ($model->casts as $field => $cast) {
					if ($cast === 'array')
						$doc[$field] = json_encode($doc[$field]);
				}
			}
			$doc += $defaults + static::defaults($doc);
		}

		$chunks = array_chunk($data, floor(999 / count($data)));
		foreach ($chunks as $chunk)
			static::insert($chunk);

		return $data;
	}

	public static function upsert(array $doc, array $where = null) {
		return parent::updateOrCreate($where ? $where : ['id' => $doc['id']], $doc);
	}

	public static function upsertBatch(array $data, array $where = null) {
		$result = [];
		foreach ($data as $key => $doc)
			$result[$key] = static::upsert($doc, $where ? $where[$key] : null);
		return $result;
	}

}
