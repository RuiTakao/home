<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreatePosts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('posts');
        $table
            // 作品名
            ->addColumn('product_name', 'string', [
                'limit' => 150,
                'null' => false,
                'comment' => '作品名',
            ])
            // 作品の説明
            ->addColumn('product_detail', 'text', [
                'null' => false,
                'limit' => 255,
                'comment' => '作品の説明',
            ])
            // urlの有り無し判定
            ->addColumn('url_flg', 'boolean', [
                'null' => false,
                'comment' => 'urlの有り無し判定',
            ])
            // urlのパス
            ->addColumn('url_path', 'string', [
                'null' => true,
                'limit' => 255,
                'comment' => 'urlのパス',
            ])
            // 画像の有り無し判定
            ->addColumn('image_flg', 'boolean', [
                'null' => false,
                'comment' => '画像の有り無し判定',
            ])
            // 画像名（alt属性）
            ->addColumn('image_alt_name', 'string', [
                'null' => true,
                'limit' => 255,
                'comment' => '画像名（alt属性）',
            ])
            // 画像のパス
            ->addColumn('image_path', 'string', [
                'null' => true,
                'limit' => 255,
                'comment' => '画像のパス',
            ])
            // 作品の表示非表示の判定
            ->addColumn('product_view_flg', 'boolean', [
                'default' => 0,
                'comment' => '作品の表示非表示の判定',
            ])
            // 作品の順番
            ->addColumn('product_order', 'integer', [
                'default' => 0,
                'limit' => 11,
                'comment' => '作品の順番',
            ])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();
    }
}
