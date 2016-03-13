<?php namespace Rahman\Blogtags\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Rainlab\Blog\Models\Post;
use Rahman\Blogtags\Models\Tag;

class SearchPosts extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * A searched tag
     *
     * @var Tag
     */
    public $tag;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageParam;

    /**
     * Slug value to display in search results for
     * @var string
     */
    public $slug;

    /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $postPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Search posts',
            'description' => 'Search posts by associated tag'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Tag slug',
                'description' => 'Display posts by associated slug of tag',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
            'pageNumber' => [
                'title'       => 'rainlab.blog::lang.settings.posts_pagination',
                'description' => 'rainlab.blog::lang.settings.posts_pagination_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'postsPerPage' => [
                'title'             => 'rainlab.blog::lang.settings.posts_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_per_page_validation',
                'default'           => '10',
            ],
            'postPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_post',
                'description' => 'rainlab.blog::lang.settings.posts_post_description',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => 'Links',
            ],
            'sortOrder' => [
                'title'       => 'rainlab.blog::lang.settings.posts_order',
                'description' => 'rainlab.blog::lang.settings.posts_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc'
            ]
        ];
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * List options of sort order
     *
     * @return array
     */
    public function getSortOrderOptions()
    {
        return [
            'title asc' => 'Title (ascending)',
            'title desc' => 'Title (descending)',
            'published_at asc' => 'Published at (ascending)',
            'published_at desc' => 'Published at (descending)',
            'created_at asc' => 'Created (ascending)',
            'created_at desc' => 'Created (descending)',
            'updated_at asc' => 'Updated (ascending)',
            'updated_at desc' => 'Updated (descending)',
        ];
    }

    public function onRun()
    {
        $this->prepareVars();

        $this->posts = $this->loadPosts();
        $this->tag = $this->loadTag();
    }

    protected function loadPosts()
    {
        /*
         * List all the posts, eager load their categories
         */
        $posts = Post::with('tags')
            ->whereHas('tags', function($tag) {
                $tag->whereSlug($this->property('slug'));
            })->listFrontEnd([
            'page'       => $this->property('pageNumber'),
            'sort'       => $this->property('sortOrder'),
            'perPage'    => $this->property('postsPerPage')
        ]);

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $posts->each(function($post){
            $post->setUrl($this->postPage, $this->controller);
        });

        return $posts;
    }

    protected function loadTag()
    {
        return Tag::where('slug', $this->property('slug'))->first();
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->slug = $this->page['slug'] = $this->property('slug');

        /*
         * Page links
         */
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
    }

}
