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
    const VALIDATE_CONTROL = "無効な操作です。";
    const NON_IMAGE_MESSAGE = "画像が選択されていません。";
    const VALIDATE_EXTENSIONS_MESSAGE = "拡張子が無効です。";
    const EXTENSIONS = ['png', 'jpg', 'jpeg'];


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
        $posts = $this->Posts->find()->order(['product_order' => 'asc']);

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
            if (!is_null($post->image_path)) {
                $this->session->write('tmp_image', 'img/tmp/' . str_replace('storage/' . $post->id . '/', '', $post->image_path));
                copy('img/' . $post->image_path, $this->session->read('tmp_image'));
                $post->image_path = $this->session->read('tmp_image');
            }
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
     * @param entity
     * 
     * @return Response|bool
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

        // urlフラグがfalseの場合
        if (!$data['url_flg']) {
            // URLのパスにnullを入れる
            $data['url_path'] = null;
        }

        // 画像フラグがfalseの場合
        if (!$data['image_flg']) {
            // 画像名にnullを入れる
            $data['image_alt_name'] = null;
        }

        // 更新時の画像パスの保存処理
        if ($this->request->getParam('action') != 'add') {

            // 更新前の画像パス
            $before_image_path = $post->image_path;

            // 画像パス
            $data['image_path'] = ($data['image_flg']) ?
                'storage/' . $post->id . '/' . str_replace('img/tmp/', '', $this->session->read('tmp_image')) :
                'storage/' . $post->id . '/no-image.png';
        } else {

            // 画像パス
            $data['image_path'] = $this->session->read('tmp_image');
        }

        try {

            // トランザクション開始
            $this->connection->begin();

            // 行ロック
            if ($post->id) {
                $this->Posts
                    ->find('all', ['conditions' => ['id' => $post->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();
            }

            // データベースに登録
            $post = $this->Posts->patchEntity($post, $data);
            $post = $this->Posts->save($post);
            if (!$post) {
                throw new DatabaseException('保存に失敗');
            }

            // 更新時の処理
            if ($this->request->getParam('action') != 'add') {

                // 画像フラグがtrueの場合
                if ($data['image_flg']) {

                    // 一時保存していた画像を保存ディレクトリに移す
                    $copy = copy($this->session->read('tmp_image'), 'img/storage/' . $post->id . '/' . str_replace('img/tmp/', '', $this->session->read('tmp_image')));
                    // 画像の保存（コピーに失敗）
                    if (!$copy) {
                        throw new DatabaseException('保存に失敗');
                    }

                    // 更新前の画像データ削除
                    unlink(WWW_ROOT . 'img/' . $before_image_path);
                }

                // トランザクション終了
                $this->connection->commit();
                $this->session->write('message', '作品を追加しました。');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->connection->commit();
            }
        } catch (DatabaseException $e) {
            $this->connection->rollback();
            $this->session->write('message', '登録に失敗しました。');
            return $this->redirect(['action' => 'index']);
        }

        /* 画像パス名と作品順はレコードのidで決定するので登録後再度更新という形を取る **/

        // 作品順
        $data['product_order'] = $post->id;

        // 画像パス
        $data['image_path'] = ($data['image_flg']) ?
            'storage/' . $post->id . '/' . str_replace('img/tmp/', '', $this->session->read('tmp_image')) :
            'storage/' . $post->id . '/no-image.png';

        try {

            // トランザクション開始
            $this->connection->begin();

            // 行ロック
            $this->Posts
                ->find('all', ['conditions' => ['id' => $post->id]])
                ->modifier('SQL_NO_CACHE')
                ->epilog('FOR UPDATE')
                ->first();

            // データベースに登録
            $post = $this->Posts->patchEntity($post, $data);
            $post = $this->Posts->save($post);
            if (!$post) {
                throw new DatabaseException('保存に失敗');
            }

            // 画像ディレクトリ作成
            // パスはwebroot/img/storage/{id}/{画像名}
            $mkdir =  mkdir(WWW_ROOT . 'img/storage/' . $post->id);
            if (!$mkdir) {
                throw new DatabaseException('保存に失敗');
            }

            // 一時保存ディレクトリからストレージへ画像を移動
            $copy = ($data['image_flg']) ?
                copy($this->session->read('tmp_image'), 'img/storage/' . $post->id . '/' . str_replace('img/tmp/', '', $this->session->read('tmp_image'))) :
                copy('img/app/no-image.png', 'img/storage/' . $post->id . '/no-image.png');
            if (!$copy) {
                throw new DatabaseException('保存に失敗');
            }

            $this->connection->commit();
        } catch (DatabaseException $e) {
            $this->connection->rollback();
            $this->session->write('message', '登録に失敗しました。');
            return $this->redirect(['action' => 'index']);
        }

        $this->session->write('message', '作品を追加しました。');
        return $this->redirect(['action' => 'index']);
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
            $this->session->write('message', self::VALIDATE_CONTROL);
            return $this->redirect(['action' => 'index']);
        }
        if ($data['image_flg'] != '0' && $data['image_flg'] != '1') {
            $this->session->write('message', self::VALIDATE_CONTROL);
            return $this->redirect(['action' => 'index']);
        }

        // エラーカウント
        // エラーの数によってバリデーションを実行させる
        $errors = 0;

        // 作品名が未入力の場合
        if ($data['product_name'] == '') {
            $errors++;
        }

        // 説明が未入力の場合
        if ($data['product_detail'] == '') {
            $errors++;
        }

        // urlフラグがtrueでurlが未入力の場合
        if ($data['url_flg'] && $data['url_path'] == '') {
            $errors++;
        }

        // imageフラグがtrueでimage_nameが未入力の場合
        if ($data['image_flg'] && $data['image_alt_name'] == '') {
            $errors++;
        }

        $image_error = null;

        // imageフラグがtrueの場合
        if ($data['image_flg']) {

            if ($data['image_path']->getClientFilename() == '' || $data['image_path']->getClientMediaType() == '') {
                // 画像選択無し

                if (!$this->session->check('tmp_image')) {
                    // 画像無し

                    $image_error = self::NON_IMAGE_MESSAGE;
                    $data['image_path'] = null;
                    $errors++;
                } else {
                    $data['image_path'] = $this->session->read('tmp_image');
                }
            } else if (!in_array(pathinfo($data['image_path']->getClientFilename())['extension'], self::EXTENSIONS)) {
                $image_error = self::VALIDATE_EXTENSIONS_MESSAGE;
                $data['image_path'] = null;
                $errors++;
            } else {
                // バリデーション反映用にファイル一時保存
                $data['image_path']->moveTo(WWW_ROOT . 'img/tmp/' . $data['image_path']->getClientFilename());
                $this->session->write('tmp_image', 'img/tmp/' . $data['image_path']->getClientFilename());
                $data['image_path'] = $this->session->read('tmp_image');
            }
        }

        // バリデーションカウントが0より多い場合、バリデーション失敗時の処理実行
        if ($errors > 0) {
            $post = $this->Posts->patchEntity($post, $data);
            $this->set('post', $post);
            $this->set('image_error',  $image_error);
            $this->session->write('message', $message);
            return true;
        }

        return false;
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
            $session_data = $this->session->read('products_id');

            // 異常系、データ数が合わない
            if (count($data['product']) != count($session_data)) {
                $this->session->write('message', '不正な動作が行われました。');
                return $this->redirect(['action' => 'index']);
            }
            // 異常系、呼び出したデータとの相違
            for ($i = 0; $i < count($session_data); $i++) {
                if (!in_array($data['product'][$i], $session_data)) {
                    $this->session->write('message', '不正な動作が行われました。');
                    return $this->redirect(['action' => 'index']);
                }
            }

            // 更新データ作成
            $save_data = [];
            foreach ($data['product'] as $index => $product) {
                $save_data[] =  [
                    'id' => $product,
                    'product_order' => $data['order'][$index],
                    'product_view_flg' => $data['status'][$index]
                ];
            }

            // 更新用のエンティティ作成
            $posts = $this->Posts->find();

            try {
                $this->connection->begin();

                // // 行ロック
                $posts->modifier('SQL_NO_CACHE')->epilog('FOR UPDATE')->toArray();

                // 一括更新
                $posts = $this->Posts->patchEntities($posts, $save_data);
                $posts = $this->Posts->saveMany($posts);
                if (!$posts) {
                    throw new DatabaseException();
                }

                $this->connection->commit();

                $this->session->write('message', '設定を反映しました。');
                $posts = $this->Posts->find()->order(['product_order' => 'asc']);
                $this->set('posts', $posts);
                return $this->redirect(['action' => 'order']);
            } catch (DatabaseException $e) {
                $this->connection->rollback();
                $this->session->write('message', '設定の更新が失敗しました。');
                return $this->redirect(['action' => 'index']);
            }
        }

        $posts = $this->Posts->find()->order(['product_order' => 'asc']);
        // post前post後でデータに相違が無いか判定するためのセッションデータ
        $session_data = [];
        foreach ($posts->toArray() as $post) {
            $session_data[] = $post->id;
        }
        $this->session->write('products_id', $session_data);

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
