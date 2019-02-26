<?php namespace Rahman\Blogtags\Components;

use DB;
use Cms\Classes\ComponentBase;
use Rainlab\Blog\Models\Post;

class RelatedPosts extends ComponentBase
{

    /**
     * A collection of related posts
     *
     * @var Collection
     */
    public $posts;

    public function componentDetails()
    {
        return [
            'name'        => 'Related posts',
            'description' => 'Display most related posts based on tags'
        ];
    }
 
    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'rainlab.blog::lang.settings.post_slug',
                'description' => 'rainlab.blog::lang.settings.post_slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
            'results' => [
                'title'             => 'Results',
                'description'       => 'Number of posts to display.',
                'default'           => 5,
                'validationPattern' => '^[1-9]+$',
                'validationMessage' => 'Only number allowed.'
            ]
        ];
    }

    public function onRun()
    {
        $this->posts = $this->loadRelatedPosts();
    }

    /**
     * Load the most related posts based on associated tags
     *
     * @return Collection
     */
    protected function loadRelatedPosts()
    {
        $post = Post::with('tags')->whereSlug($this->property('slug'))->first();

        if (is_null($post) || !$post->tags->count()) return;

        $tagIds = $post->tags->lists('id');

        // a collection of related posts
        $query = Post::isPublished()
            ->where('id', '<>', $post->id)
            ->whereHas('tags', function($tag) use ($tagIds) {
                $tag->whereIn('id', $tagIds);
            })
            ->with('tags');

        $orderBy = DB::raw('(
            select count(*) from `rahman_blogtags_posts_tags`
            where `rahman_blogtags_posts_tags`.`post_id` = `rainlab_blog_posts`.`id`
            and `rahman_blogtags_posts_tags`.`tag_id` in ('.implode(', ', $tagIds).'))');

        // order by most related tags
        $query->orderby($orderBy, 'desc');

        if ($take = intVal($this->property('results')))
            $query->take($take);

        return $query->get()->each(function($post) {
            $post->setUrl($this->postPage, $this->controller);

            $post->categories->each(function($category) {
                $category->setUrl($this->categoryPage, $this->controller);
            });
        });
    }
    
}
