<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use Cake\Database\Exception\DatabaseException;
use Cake\Event\EventInterface;
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

    const VALIDATE_MESSAGE = "入力に不備があります。";

    public function initialize(): void
    {
        parent::initialize();

        $this->connection = TableRegistry::getTableLocator()->get($this->Posts->getAlias())->getConnection();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        if ($this->request->is('get')) {
            $this->tmp_image_delete();
        }
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

        // postの場合
        if ($this->request->is('post')) {
            // 登録処理
            return $this->save_property($post);
        }

        // 画像のエラーメッセージ
        $this->set('image_error', null);

        $this->set('post', $post);
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
        $post = $this->Posts->find('all', ['conditions' => ['id' => $id]])->first();

        // postの場合
        if ($this->request->is(['patch', 'post', 'put'])) {
            // 登録処理
            return $this->save_property($post);
        } else {
            $this->session->write('tmp_image', 'img/tmp/' . str_replace('storage/', '', $post->image_path));
            copy('img/' . $post->image_path, $this->session->read('tmp_image'));
            $post->image_path = Router::url('/' . $this->session->read('tmp_image'),  true);
        }

        // 画像のエラーメッセージ
        $this->set('image_error', null);

        $this->set('post', $post);
        $this->render('add');
    }

    /**
     * save_property Method
     * 
     * バリデーション処理
     * 
     * @param request
     * @param entity
     * 
     * @return bool
     */
    private function save_property($post)
    {

        // リクエストデータ取得
        $data = $this->request->getData();

        // バリデーション
        if ($this->validate($data, $post)) {
            if ($this->request->getParam('action') == 'edit') {
                $this->render('add');
            }
            return;
        }

        /**
         * バリデーションエラー時は以下は通らない
         * 
         * status、url（url_flg = false）、image_name（image_flg = false）、image_path（image_flg = false）
         * の場合の処理は以下で強制的にnullを入れる為、バリデーションチェックは行わない
         */

        try {

            // トランザクション開始
            $this->connection->begin();

            // url
            // urlフラグがfalesならurlにnullを入れる
            if (!$data['url_flg']) {
                $data['url'] = null;
            }

            // 画像名
            // 画像フラグがfalesなら画像名、画像パスにnullを入れる
            if (!$data['image_flg']) {
                $data['image_name'] = null;
                $data['image_path'] = null;
            } else {
                // 画像
                $data['image_path'] = $this->save_image_path();
            }


            if ($this->request->getParam('action') == 'add') {
                // ステータスはデフォルトでfalse
                $data['status'] = false;

                $query = $this->Posts->find();
                $query = $query->select(['post_order' => $query->func()->max('post_order')])->first();
                if (!empty($query)) {
                    $data['post_order'] = $query->post_order + 1;
                } else {
                    $data['post_order'] = 1;
                }
            }

            $post = $this->Posts->patchEntity($post, $data);
            if ($this->Posts->save($post)) {
                $this->session->write('message', '作品を追加しました。');
                $this->connection->commit();
                $this->tmp_image_delete();
                return $this->redirect(['action' => 'index']);
            } else {
                throw new DatabaseException;
            }
        } catch (DatabaseException $e) {
            $this->connection->rollback();
            $this->session->write('message', '登録に失敗しました。');
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * save_image_path Method
     * 
     * バリデーション処理
     * 
     * @param request
     * @param entity
     * 
     * @return bool
     */
    private function save_image_path()
    {
        // 画像データ取得
        // バリデーションで未入力チェックはしているのでここでは行わない
        $image = str_replace('img/tmp/', '', $this->session->read('tmp_image'));

        // 画像パス
        $image_path = 'storage/' . $image;

        // 画像保存
        copy($this->session->read('tmp_image'), 'img/' . $image_path);

        return $image_path;
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

        $message = self::VALIDATE_MESSAGE;

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

        $image_error = null;

        if ($this->request->getParam('action') == 'add') {
            // 画像フラグがtrueの場合
            if ($data['image_flg']) {

                // 画像名が未入力でエラー
                if ($data['image_name'] == '') {
                    $errors++;
                }

                if ($data['image_path']->getClientFilename() == '' || $data['image_path']->getClientMediaType() == '') {
                    // 画像が未設定

                    // 一時保存画像の画像のパス
                    $data['image_path'] = $this->tmp_image_path($data);
                    // 一時保存画像の画像のパスも無ければエラー
                    if (is_null($data['image_path'])) {
                        $image_error = '画像が設定されていません。';
                        $errors++;
                    } else {
                        $tmp_filename = str_replace('img/tmp/', '', $this->session->read('tmp_image'));
                        if (file_exists(WWW_ROOT . 'img/storage/' . $tmp_filename)) {
                            // 画像重複エラー
                            $image_error = '画像が重複しています。画像を変更するか、画像のファイル名を変更してください。';
                            $message = $image_error;

                            $data['image_path'] = $this->session->read('tmp_image');
                            $errors++;
                        }
                    }
                } else {
                    // 画像がある場合

                    $filename = pathinfo($data['image_path']->getClientFilename())['extension'];
                    $extention = ['png', 'jpg', 'jpeg'];

                    if (!in_array($filename, $extention)) {
                        // 拡張子が無効です。
                        $image_error = '拡張子が無効です。';

                        $data['image_path'] = null;
                        $this->tmp_image_delete();
                        $errors++;
                    } elseif (file_exists(WWW_ROOT . 'img/storage/' . $data['image_path']->getClientFilename())) {
                        // 画像重複エラー
                        $image_error = '画像が重複しています。画像を変更するか、画像のファイル名を変更してください。';
                        $message = $image_error;

                        $data['image_path'] = $this->tmp_image_path($data);
                        $errors++;
                    } else {
                        $data['image_path'] = $this->tmp_image_path($data);
                    }
                }
            } else {
                $data['image_path'] = null;
                $this->tmp_image_delete();
            }
        }

        if ($this->request->getParam('action') == 'edit') {
        }


        // バリデーションカウントが0より多い場合、バリデーション失敗時の処理実行
        if ($errors > 0) {

            $post = $this->Posts->patchEntity($post, $data);
            $this->set('post', $post);
            $this->set('image_error', $image_error);
            $this->session->write('message', $message);
            return true;
        }

        return false;
    }

    /**
     * tmp_image_path Method
     * 
     * 一時保存画像の画像のパス
     * 
     * @param request
     * 
     * @return string|null
     */
    private function tmp_image_path($data): string|null
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

    private function tmp_image_delete(): void
    {
        if ($this->session->check('tmp_image')) {
            if (file_exists(WWW_ROOT . $this->session->read('tmp_image'))) {
                unlink($this->session->read('tmp_image'));
            }
            $this->session->delete('tmp_image');
        }
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
