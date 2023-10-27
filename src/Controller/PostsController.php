<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->viewBuilder()->disableAutoLayout();

        $posts = $this->Posts->find()
            ->where([
                'product_view_flg' => true,
                'product_order >' => 0
            ])
            ->order(['product_order' => 'asc']);

        $this->set(['posts' => $posts]);
    }
}
