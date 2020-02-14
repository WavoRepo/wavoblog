<?php

return [

    'services' => [
        'user' => [
            'name' => 'Users',
            'icon' => 'fa fa-users',
            'slug' => 'users',
            'desc' => 'List of registered user.'
        ],
        'security' => [
            'name' => 'Security',
            'icon' => 'fa fa-shield',
            'slug' => 'security',
            'desc' => 'Profile security, xchange password.'
        ],
        'blog' => [
            'name' => 'Blog Post',
            'icon' => 'fa fa-list-alt',
            'slug' => 'blog',
            'desc' => 'List of blog post created by registered user.'
        ],
        'blog-catrgory' => [
            'name' => 'Blog Post Categories',
            'icon' => 'fa fa-tags',
            'slug' => 'blog/categories',
            'desc' => 'List of blog post category created by registered user.'
        ]
    ]
];
