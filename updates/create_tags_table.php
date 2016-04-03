<?php namespace Rahman\Blogtags\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTagsTable extends Migration
{

    public function up()
    {
        if ( ! Schema::hasTable('rahman_blogtags_tags')){
            Schema::create('rahman_blogtags_tags', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 25)->nullable();
                $table->string('slug', 27)->nullable()->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('rahman_blogtags_posts_tags')) {
            Schema::create('rahman_blogtags_posts_tags', function($table)
            {
                $table->engine = 'InnoDB';
                $table->integer('tag_id')->unsigned();
                $table->integer('post_id')->unsigned();
                $table->index(['tag_id', 'post_id']);
                $table->foreign('tag_id')->references('id')->on('rahman_blogtags_tags')->onDelete('cascade');
                $table->foreign('post_id')->references('id')->on('rainlab_blog_posts')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('rahman_blogtags_posts_tags');
        Schema::dropIfExists('rahman_blogtags_tags');
    }

}
