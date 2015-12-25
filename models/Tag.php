<?php namespace Rahman\Blogtags\Models;

use Model;

/**
 * Tag Model
 */
class Tag extends Model
{

    use \October\Rain\Database\Traits\Sluggable;

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
    protected $fillable = ['name'];

    /**
     * @var array slugs fields
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'posts' => ['RainLab\Blog\Models\Post', 'table' => 'rahman_blogtags_posts_tags']
    ];

    /**
     * Lists tags for the page
     *
     * @param array $sortOrder 
     * @return self
     */
    public function scopeListTags($query, $sortOrder)
    {
        $sortOrder = explode(' ', $sortOrder);
        $sortedBy = $sortOrder[0];
        $direction = $sortOrder[1];

        return $query->orderBy($sortedBy, $direction);
    }
    
}
