<?php

namespace Cubitworx\Laravel\Model\Traits;

use Carbon\Carbon;

trait ExtendedModel {

	public static function applyMutations(array $events, array $doc) {

		$model = new static();
		if (!empty($model->casts)) {
			foreach ($model->casts as $field => $cast) {
				if (($cast === 'array') && array_key_exists($field, $doc))
					$doc[$field] = json_encode($doc[$field]);
			}
		}

		$obj = (object)$doc;
		$name = static::class;
		foreach ($events as $event) {
			foreach (static::getEventDispatcher()->getListeners("eloquent.{$event}: {$name}") as $listener)
				$listener($event, [$obj]);
		}

		return (array)$obj;
	}

	public static function defaults(array $doc) {
		return [
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		];
	}

	/**
	 * Apply DB mutation defaults and batch insert records
	 *
	 * Chunk insert for SQLlite which is currently limited to bulk insert limit of 1000 records
	 */
	public static function insertBatch(array $data) {
		foreach ($data as $key => $doc) {
			$doc += static::defaults($doc);
			$data[$key] = static::applyMutations(['saving', 'updating'], $doc);
		}

		$chunks = array_chunk($data, floor(999 / count($data)));

		$result = [];
		foreach ($chunks as $chunk)
			$result[$key] = static::insert($chunk);
		return $result;
	}

	public static function upsert(array $doc, array $where = null) {
		$doc = static::applyMutations(['saving', 'updating'], $doc);
		return parent::updateOrCreate($where ? $where : ['id' => $doc['id']], $doc);
	}

	public static function upsertBatch(array $data, array $where = null) {
		$result = [];
		foreach ($data as $key => $doc)
			$result[$key] = static::upsert($doc, $where ? $where[$key] : null);
		return $result;
	}

}
