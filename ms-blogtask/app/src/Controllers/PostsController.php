<?php

namespace MSTask\Blog\Controllers;

use PageController;
use Parsedown;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

class PostsController extends PageController
{
    private static $url_handlers = [
        '$Post' => 'index'
    ];

    public function index($request)
    {
        $postSlug = $request->param('Post');

        if (!$postSlug) {
            $posts = $this->getAllPosts();

            $postArray = ArrayList::create();

            foreach ($posts as $post) {
                $postArray->push(
                    new ArrayData([
                        'Title' => $this->getPostTitle($post),
                        'Link' => 'posts/' . substr($post, 0, -3)
                    ])
                );
            }

            return $this->customise([
                'Title' => 'Posts',
                'Posts' => $postArray
            ])->renderWith([
                'Posts',
                'Page'
            ]);
        } else {
            $post = $this->getPost($postSlug);

            if ($post) {
                $postContent = '';
                $markdownParser = new Parsedown();
                $markdownParser->setBreaksEnabled(true);
                
                $postTitle = substr($post[1], 7);
                $contentArray = array_slice($post, 6);

                foreach ($contentArray as $line) {
                    $postContent .= $markdownParser->text($line);
                }

                return $this->customise([
                    'Title' => $postTitle,
                    'Content' => $postContent
                ])->renderWith([
                    'Post',
                    'Page'
                ]);
            } else {
                return $this->httpError(404, 'This post could not be found.');
            }
        }
    }

    public function getAllPosts()
    {
        return array_slice(scandir('assets/posts'), 2);
    }

    public function getPost($postSlug)
    {
        $posts = $this->getAllPosts();

        foreach ($posts as $post) {
            if ($post == $postSlug . '.md') {
                return file('assets/posts/' . $postSlug . '.md');
            }
        }

        return false;
    }

    public function getPostTitle($post)
    {
        $post = file('assets/posts/' . $post);

        return substr($post[1], 7);
    }
}
