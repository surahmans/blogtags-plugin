<?php
use Rahman\BlogTags\Models\Tag;
use RainLab\Blog\Models\Post;

/**
 * get available tags and assigned tags by post id
 */
Route::get('api/blog/post/{postId?}', ['as' => 'api.get.taglist', function($postId = 0) {
    $availableTags = Tag::all()->lists('name');
    $assignedTags  = Tag::whereHas('posts', function($q) use ($postId) { 
        $q->where('id', $postId);
    })->lists('name');

    $response = [
        'assignedTags'  => $assignedTags,
        'availableTags' => $availableTags
    ];

    return Response::json($response);
}]);

