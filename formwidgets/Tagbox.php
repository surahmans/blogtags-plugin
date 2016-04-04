<?php namespace Rahman\BlogTags\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Config;
use Rahman\BlogTags\Models\Tag;

class TagBox extends FormWidgetBase
{
    public function widgetDetails()
    {
        return [
            'name'        => 'Tag Box Field',
            'description' => 'Tagbox using AJAX'
        ];
    }

    /**
     * render the form widget
     */
    public function render() {
        $this->prepareVars();
        return $this->makePartial('widgets');
    }

    /**
     * prepare the variables
     *
     * @return void
     */
    public function prepareVars()
    {
        $this->vars['id'] = $this->model->id;
        $this->vars['tags'] = $this->loadTags();
    }

    public function loadTags()
    {
        $tags = [];

        if ($this->model->tags) {
            foreach($this->model->tags as $tag) {
                $tags[] = $tag->name;
            }
        }

        return implode(',', $tags);
    }

    /**
     * load assets widgets
     */
    public function loadAssets()
    {
        $this->addCss('css/jquery.taghandler.css');
        $this->addCss('css/jquery-ui-1.8.2.custom.css');

        $this->addJs('js/jquery-ui-11.min.js');
        $this->addJs('js/jquery.taghandler.min.js');
    }

    /**
     * get tag id if exists or create it if not exists
     *
     * @param string $value
     *
     * @return array
     */
    public function getSaveValue($value)
    {
        $tags = explode(",", implode(",", $value));
        $ids = [];

        foreach ($tags as $name) {
            if (empty($name)) {
                continue;
            }
            $created = Tag::firstOrCreate(['name' => $name]);
            $ids[] = $created->id;
        }

        return $ids;
    }
    
}
