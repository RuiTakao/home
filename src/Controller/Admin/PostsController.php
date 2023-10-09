<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $posts = $this->Posts->find()->order(['post_order' => 'asc']);

        $this->set(compact('posts'));
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $post = $this->Posts->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('post'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $post = $this->Posts->newEmptyEntity();
        $errors = [];
        if ($this->request->is('post')) {

            $data = $this->request->getData();

            if (($data['url_flg'] && $data['url'] != "") || ($data['image_flg'] && $data['image_path']) || ($data['image_flg'] && $data['image_name'])) {
                // $this->Session->setFlash('My message', 'alert');
                // return $this->redirect(['action' => 'index']);
            } 
            // else if (!$data['image_flg'] && ) {
                
            // }

            // $image = $data['image_path'];
            // $image_path = 'tmp/' . $image->getClientFilename();
            // if (file_exists(WWW_ROOT . 'img/' . $image_path)) {
            //     $this->Flash->error(__('ファイルが存在します'));
            //     return;
            // }
            // $image->moveto(WWW_ROOT . 'img/' . $image_path);

            $data['image_path'] = '';

            $post = $this->Posts->patchEntity($post, $data);
            if ($this->Posts->save($post)) {

                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(['errors' => $errors]);
        $this->set(compact('post'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        $post = $this->Posts->find()->first();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            $image = $data['image_path'];
            $image_path = 'tmp/' . $image->getClientFilename();
            if (file_exists(WWW_ROOT . 'img/' . $image_path)) {
                $this->Flash->error(__('ファイルが存在します'));
                return;
            }
            $image->moveto(WWW_ROOT . 'img/' . $image_path);

            $data['image_path'] = $image_path;

            $post = $this->Posts->patchEntity($post, $data);
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $this->set(compact('post'));
    }

    public function order() {
        $data = $this->request->getData();
        foreach ($data['product'] as $index => $product) {
            $post = $this->Posts->find()->select(['id' => $product])->first();

            $post = $this->Posts->patchEntity($post, ['post_order' => $data['order'][$index]]);
            if ($this->Posts->save($post)) {
                // $this->Flash->success(__('The post has been saved.'));

                // return $this->redirect(['action' => 'index']);
            }
        }


        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $post = $this->Posts->get($id);
        if ($this->Posts->delete($post)) {
            $this->Flash->success(__('The post has been deleted.'));
        } else {
            $this->Flash->error(__('The post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    
}
