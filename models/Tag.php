<?php namespace Rahman\Blogtags\Models;

use Model;

/**
 * Tag Model
 */
class Tag extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rahman_blogtags_tags';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'posts' => ['RainLab\Blog\Models\Post', 'table' => 'rahman_blogtags_posts_tags']
    ];

}
