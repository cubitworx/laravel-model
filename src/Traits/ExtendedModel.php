<?php

namespace Cubitworx\Laravel\Model\Traits;

use Carbon\Carbon;

trait ExtendedModel {

	public static function applyMutations(array $events, array $doc) {
		$model = new static();
		if (!empty($model->casts)) {
			foreach ($model->casts as $field => $cast) {
				if (($cast === 'array') && array_key_exists($field, $doc) && is_array($doc[$field]))
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

	/**
	 * Apply DB mutations and batch insert records
	 *
	 * Chunk insert for SQLlite which is currently limited to bulk insert limit of 1000 records
	 */
	public static function insertBatch(array $data) {
		foreach ($data as $key => $doc) {
			$doc += [
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now(),
			];
			$data[$key] = static::applyMutations(['saving'], $doc);
		}

		$chunks = array_chunk($data, floor(999 / count($data)));

		$result = [];
		foreach ($chunks as $chunk)
			$result[$key] = static::insert($chunk);
		return $result;
	}

	public static function upsert(array $doc, array $where = null) {
		return parent::_upsert($doc, $where);
	}

	public static function upsertBatch(array $data, array $where = null) {
		$result = [];
		foreach ($data as $key => $doc)
			$result[$key] = static::upsert($doc, $where ? ($where[$key] ?? null) : null);
		return $result;
	}

	protected static function _upsert(array $doc, array $where = null) {
		return parent::updateOrCreate($where ?? ['id' => $doc['id']], $doc);
	}

}
