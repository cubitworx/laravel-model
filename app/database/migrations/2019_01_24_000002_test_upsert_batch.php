<?php

use App\Model;
use Illuminate\Database\Migrations\Migration;

class TestUpsertBatch extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Model\Page::upsertBatch([
			['url' => '/test-2', 'title' => 'upsertBatch', 'options' => ['upsertBatch']],
			['url' => '/test-2/insert', 'title' => 'upsertBatch', 'options' => ['upsertBatch']],
		]);
	}

}
