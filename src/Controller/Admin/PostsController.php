<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use Cake\Database\Exception\DatabaseException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

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

            // ステータスはデフォルトでfalse
            $data['status'] = false;

            // バリデーション
            if ($this->validate($data, $post)) {
                return;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // url
                // urlフラグがfalesならurlにnullを入れる
                if (!$data['url_flg']) {
                    $data['url'] = null;
                }

                // 画像名
                // 画像フラグがfalesなら画像名にnullを入れる
                if (!$data['image_flg']) {
                    $data['image_name'] = null;
                }

                // 画像
                $data['image_path'] = $this->save_image_path($data);

                $query = $this->Posts->find();
                $query = $query->select(['post_order' => $query->func()->max('post_order')])->first();
                if (!empty($query)) {
                    $data['post_order'] = $query->post_order + 1;
                } else {
                    $data['post_order'] = 1;
                }

                $post = $this->Posts->patchEntity($post, $data);
                if ($this->Posts->save($post)) {
                    $this->session->write('message', '作品を追加しました。');
                }
                $this->connection->commit();
                $this->session->delete('tmp_image');
                return $this->redirect(['action' => 'index']);
            } catch (DatabaseException $e) {
                $this->connection->rollback();
                $this->session->write('message', '登録に失敗しました。');
                return $this->redirect(['action' => 'index']);
            }
        }

        // 画像のエラーメッセージ
        $this->set('image_error', null);
        
        $this->set('post', $post);
    }

    private function save_image_path($data)
    {
        // 画像フラグがtrueの場合
        if ($data['image_flg']) {

            // 画像データ取得
            // バリデーションで未入力チェックはしているのでここでは行わない
            $image = $data['image_path'];

            // 画像パス
            $image_path = 'storage/' . $image->getClientFilename();

            $image->moveto(WWW_ROOT . 'img/' . $image_path);

            return $image_path;
        }

        return null;
    }

    /**
     * validate Method
     * 
     * バリデーション処理
     * 
     * @param request
     * @param entity
     * 
     * @return bool
     */
    private function validate($data, $post): bool
    {
        $image_error = null;

        // あり得ない値が入ってきた場合
        if ($data['url_flg'] != '0' && $data['url_flg'] != '1') {
            $this->session->write('message', '無効な操作です。');
            return $this->redirect(['action' => 'index']);
        }
        if ($data['image_flg'] != '0' && $data['image_flg'] != '1') {
            $this->session->write('message', '無効な操作です。');
            return $this->redirect(['action' => 'index']);
        }

        // エラーカウント
        // エラーの数によってバリデーションを実行させる
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
        if ($data['url_flg'] && $data['url'] == '') {
            $errors++;
        }

        // 画像フラグがtrueで画像名が未入力の場合
        if ($data['image_flg'] && $data['image_name'] == '') {
            $errors++;
        }
        // 画像フラグがtrueで画像が未選択の場合
        if (
            ($data['image_flg'] && $data['image_path']->getClientFilename() == '') ||
            ($data['image_flg'] && $data['image_path']->getClientMediaType() == '')
        ) {
            $image_error = '画像が選択されていません。';
            $errors++;
        } elseif ($data['image_path']->getClientFilename() != '' || $data['image_path']->getClientMediaType() != '') {
            if (file_exists(WWW_ROOT . 'img/storage/' . $data['image_path']->getClientFilename())) {
                $image_error = '画像が重複しています。画像を変更するか、画像のファイル名を変更してください。';
                $this->session->write('message', $image_error);
                $errors++;
            }
        }

        // バリデーションカウントが0より多い場合、バリデーション失敗時の処理実行
        if ($errors > 0) {

            // バリデーション失敗時の処理
            if (is_null($image_error)) {
                $data['image_path'] = $this->image_when_validationFailed($data);
                $this->session->write('message', '入力に不備があります。');
                $this->set('image_error', $image_error);
            } else {
                $data['image_path'] = null;
            }

            $post = $this->Posts->patchEntity($post, $data);
            $this->set('post', $post);
            return true;
        }

        return false;
    }

    /**
     * image_when_validationFailed Method
     * 
     * バリデーション失敗時の画像の処理
     * 
     * @param request
     * 
     * @return string|null
     */
    private function image_when_validationFailed($data): string|null
    {
        if ($data['image_flg']) {
            // 画像フラグ有りの場合

            // requestから画像情報取得
            $image = $data['image_path'];

            // 画像入力あるか判定
            if ($image->getClientFilename() != '' || $image->getClientMediaType() != '') {
                // 画像入力有り

                // 画像パス
                $image_path = 'img/tmp/' . $image->getClientFilename();

                // 画像一時保存されているか確認、されていれば削除
                if ($this->session->read('tmp_image') && file_exists($this->session->read('tmp_image'))) {
                    unlink($this->session->read('tmp_image'));
                }

                // 画像、一時保存
                $image->moveto(WWW_ROOT . $image_path);
                // 一時保存した画像のパスをセッションに書き込み
                $this->session->write('tmp_image', $image_path);
                // 一時保存した画像のパスをエンティティに渡す
                return Router::url('/' . $image_path,  true);
            } else {
                // 画像入力無し

                // 画像、一時保存用のセッションがあり、画像が保存されていたら、その画像をエンティティに渡す
                if ($this->session->read('tmp_image') && file_exists($this->session->read('tmp_image'))) {
                    return Router::url('/' . $this->session->read('tmp_image'),  true);
                }
            }
        } else {
            // 画像フラグ無しの場合

            // 画像一時保存用のセッションあるか確認、あれば削除
            if ($this->session->read('tmp_image')) {

                // 画像一時保存用のセッションのパスに画像あるか確認、あれば削除
                if (file_exists($this->session->read('tmp_image'))) {
                    unlink($this->session->read('tmp_image'));
                }
                $this->session->delete('tmp_image');
            }
        }
        return null;
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
            if ($this->validate($data, $post)) {
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
            } catch (DatabaseException $e) {
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
