<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use Cake\Database\Exception\DatabaseException;
use Cake\ORM\TableRegistry;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AdminController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->connection = TableRegistry::getTableLocator()->get($this->Posts->getAlias())->getConnection();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $posts = $this->Posts->find()->order(['post_order' => 'asc']);

        $this->set('posts', $posts);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // エンティティの作成
        $post = $this->Posts->newEmptyEntity();

        if ($this->request->is('post')) {
            // postの場合

            // リクエストデータ取得
            $data = $this->request->getData();

            // バリデーション
            if ($this->validate($data)) {
                $data['image_path'] = null;
                $post = $this->Posts->patchEntity($post, $data);
                $this->set('post', $post);
                return;
            }

            // urlの確認
            if ($data['url_flg'] == '0') {
                $data['url'] = null;
            }

            // 画像の確認
            if ($data['image_flg'] == '0') {
                $data['image_name'] = null;
                $data['image_path'] = null;
            } else {
                $image = $data['image_path'];

                $image_path = 'tmp/' . $image->getClientFilename();

                if (file_exists(WWW_ROOT . 'img/' . $image_path)) {
                    $image_path = $image_path . "（1）";
                }
                $image->moveto(WWW_ROOT . 'img/' . $image_path);

                $data['image_path'] = $image_path;
            }

            $data['status'] = 0;

            // try {
                // } catch (DatabaseException $e) {
                    //     $this->session->write('message', 'データベースに障害が起こりました。');
                    //     return $this->redirect(['action' => 'index']);
                    // }
                    try {
            $query = $this->Posts->find();
            $query = $query->select(['post_order' => $query->func()->max('post_order')])->first();
            if (!empty($query)) {
                $data['post_order'] = $query->post_order + 1;
            } else {
                $data['post_order'] = 1;
            }
        }catch (DatabaseException $e) {
                $this->session->write('message', 'データベースに障害が起こりました。');
                return $this->redirect(['action' => 'index']);
            }

            try {
                $this->connection->begin();

                $post = $this->Posts->patchEntity($post, $data);
                if ($this->Posts->save($post)) {
                    $this->session->write('message', '作品を追加しました。');
                }
                $this->connection->commit();
                return $this->redirect(['action' => 'index']);
            }catch (DatabaseException $e) {
                $this->connection->rollback();
                $this->session->write('message', '登録に失敗しました。');
                return $this->redirect(['action' => 'index']);
            }
        }

        $this->set('post', $post);
    }

    private function validate($data)
    {

        // あり得ない値が入ってきた場合
        if ($data['url_flg'] != '0' && $data['url_flg'] != '1') {
            return $this->redirect(['action' => 'index']);
        }
        if ($data['image_flg'] != '0' && $data['image_flg'] != '1') {
            return $this->redirect(['action' => 'index']);
        }

        // エラーカウント
        $errors = 0;

        // 作品名が未入力の場合
        if ($data['title'] == '') {
            $errors++;
        }

        // 説明が未入力の場合
        if ($data['body'] == '') {
            $errors++;
        }

        // urlフラグがtrueでurlが未入力の場合
        if ($data['url_flg'] == '1' && $data['url'] == '') {
            $errors++;
        }

        // 画像フラグがtrueで画像名が未入力の場合
        if ($data['image_flg'] == '1' && $data['image_name'] == '') {
            $errors++;
        }
        // 画像フラグがtrueで画像が未選択の場合
        if (
            ($data['image_flg'] == '1' && $data['image_path']->getClientFilename() == '') ||
            ($data['image_flg'] == '1' && $data['image_path']->getClientMediaType() == '')
        ) {
            $data['image_path'] = null;
            $errors++;
        }

        // エラーの数によってバリデーションを実行させる
        if ($errors > 0) {
            return true;
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // エンティティの作成
        $post = $this->Posts->find('all', ['id' => $id])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // リクエストデータ取得
            $data = $this->request->getData();

            // バリデーション
            if ($this->validate($data)) {
                $data['image_path'] = null;
                $post = $this->Posts->patchEntity($post, $data);
                $this->set('post', $post);
                return;
            }

            // urlの確認
            if ($data['url_flg'] == '0') {
                $data['url'] = null;
            }

            // 画像の確認
            if ($data['image_flg'] == '0') {
                $data['image_name'] = null;
                $data['image_path'] = null;
            } else {
                $image = $data['image_path'];

                $image_path = 'tmp/' . $image->getClientFilename();

                if (file_exists(WWW_ROOT . 'img/' . $image_path)) {
                    $image_path = $image_path . "（1）";
                }
                $image->moveto(WWW_ROOT . 'img/' . $image_path);

                $data['image_path'] = $image_path;
            }

            try {
                $this->connection->begin();

                $post = $this->Posts->patchEntity($post, $data);
                if ($this->Posts->save($post)) {
                    $this->session->write('message', '作品を編集しました。');
                }
                $this->connection->commit();
            }catch (DatabaseException $e) {
                $this->connection->rollback();
                $this->session->write('message', '編集に失敗しました。');
                return $this->redirect(['action' => 'index']);
            }
        }

        $this->set('post', $post);
        $this->render('add');
    }

    public function order()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            try {
                $this->connection->begin();

                foreach ($data['product'] as $index => $product) {
                    $post = $this->Posts->find()->select(['id' => $product])->first();

                    $post = $this->Posts->patchEntity($post, ['post_order' => $data['order'][$index], 'status' => $data['status'][$index]]);
                    if (!$this->Posts->save($post)) {
                        throw new DatabaseException();
                    }
                }

                $this->connection->commit();

                $this->session->write('message', '設定を反映しました。');
                $posts = $this->Posts->find()->order(['post_order' => 'asc']);
                $this->set('posts', $posts);
                return $this->redirect(['action' => 'order']);
            } catch (DatabaseException $e) {
                $this->connection->rollback();
                $this->session->write('message', '設定の更新が失敗しました。');
                return $this->redirect(['action' => 'index']);
            }
        }

        $posts = $this->Posts->find()->order(['post_order' => 'asc']);
        $this->set('posts', $posts);
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

        // return $this->redirect(['action' => 'index']);
        return;
    }
}
