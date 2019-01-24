<?php

use App\Model;
use Illuminate\Database\Migrations\Migration;

class TestInsertBatch extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Model\Page::insertBatch([
			['url' => '/test-1', 'title' => 'insertBatch', 'options' => ['insertBatch']],
			['url' => '/test-2', 'title' => 'insertBatch', 'options' => ['insertBatch']],
			['url' => '/test-3', 'title' => 'insertBatch', 'options' => ['insertBatch']],
		]);
	}

}
