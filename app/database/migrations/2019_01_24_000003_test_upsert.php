<?php

use App\Model;
use Illuminate\Database\Migrations\Migration;

class TestUpsert extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Model\Page::upsert(['url' => '/test-3', 'title' => 'upsert', 'options' => ['upsert']]);
		Model\Page::upsert(['url' => '/test-3/insert', 'title' => 'upsert', 'options' => ['upsert']]);
	}

}
