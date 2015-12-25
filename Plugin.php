<?php namespace Rahman\BlogTags;

use System\Classes\PluginBase;

/**
 * BlogTags Plugin Information File
 */
class Plugin extends PluginBase
{

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

}
