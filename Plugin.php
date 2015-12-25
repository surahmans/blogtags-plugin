<?php namespace Rahman\BlogTags;

use System\Classes\PluginBase;
use RainLab\Blog\Models\Post as PostModel;

/**
 * BlogTags Plugin Information File
 */
class Plugin extends PluginBase
{

    // required plugins
    public $require = ['RainLab.Blog'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'BlogTags',
            'description' => 'Blog tags for rainlab.blog plugin',
            'author'      => 'Rahman',
            'icon'        => 'icon-tags'
        ];
    }

    /**
     * Extend rainlab.blog plugin
     *
     * @return void
     */
    public function boot()
    {
        PostModel::extend(function($model) {
            $model->belongsToMany = [
                'tags' => ['Rahman\BlogTags\Models\Tag', 'table' => 'rahman_blogtags_posts_tags']
            ];
        });
    }

}
