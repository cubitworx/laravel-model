<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('pages', function (Blueprint $table) {
			$table->increments('id');

			$table->text('options')->nullable();
			$table->string('saving')->nullable();
			$table->string('title')->nullable();
			$table->string('updating')->nullable();
			$table->string('url')->nullable();

			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('pages');
	}

}
