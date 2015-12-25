<?php namespace Rahman\Blogtags\Components;

use Cms\Classes\ComponentBase;
use Rahman\Blogtags\Models\Tag;

class Tags extends ComponentBase
{

    /**
     * A collection of tags to display
     *
     * @return Collection
     */
    public $tags;

    public function componentDetails()
    {
        return [
            'name'        => 'Tags list',
            'description' => 'Display a list of tags'
        ];
    }

    public function defineProperties()
    {
        return [
            'results' => [
                'title'             => 'Results',
                'description'       => 'Number of tags to display (Set zero to display all).',
                'default'           => 0,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Only number allowed.'
            ],
            'sortOrder' => [
                'title'       => 'Tag order',
                'description' => 'Attribute on which the tags should be ordered.',
                'type'        => 'dropdown',
                'default'     => 'created_at desc'
            ],
            'emptyTag' => [
                'title'       => 'Display empty tag',
                'description' => 'Display tag although without post associated with',
                'type'        => 'checkbox',
                'default'     => false
            ]
        ];
    }

    public function onRun()
    {
        $this->tags = $this->loadTags();
    }
    
    protected function loadTags()
    {
        $query = Tag::with('posts');

        if ( ! $this->property('emptyTag')) {
            $query->has('posts', '>=', '1');
        }

        if ($take = intVal($this->property('results')))
            $query->take($take);

        $query->listTags($this->property('sortOrder'));

        return $query->get();
    }
    

    /**
     * List options of sort order
     *
     * @return array
     */
    public function getSortOrderOptions()
    {
        return [
            'name asc' => 'Name (ascending)',
            'name desc' => 'Name (descending)',
            'created_at asc' => 'Created (ascending)',
            'created_at desc' => 'Created (descending)',
            'updated_at asc' => 'Updated (ascending)',
            'updated_at desc' => 'Updated (descending)',
        ];
    }
}
