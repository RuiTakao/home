<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Posts Model
 *
 * @method \App\Model\Entity\Post newEmptyEntity()
 * @method \App\Model\Entity\Post newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Post[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post get($primaryKey, $options = [])
 * @method \App\Model\Entity\Post findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Post[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PostsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('posts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->maxLength('product_name', 150, '作品名は150文字以内で入力してください。')
            ->notEmptyString('product_name', '作品名は必須です。');

        $validator
            ->maxLength('product_detail', 255, '作品名は255文字以内で入力してください。')
            ->notEmptyString('product_detail', '説明は必須です。');

        $validator
            ->integer('url_flg')
            ->notEmptyString('url_flg');

        $validator
            ->maxLength('url_path', 255)
            ->url('url_path', 'URLを入力してください。', function ($context) {
                if ($context['data']['url_flg']) {
                    return true;
                }
                return false;
            })
            ->notEmptyString('url_path', 'URLを入力してください。', function ($context) {
                if ($context['data']['url_flg']) {
                    return true;
                }
                return false;
            });

        $validator
            ->integer('image_flg')
            ->notEmptyString('image_flg');

        $validator
            ->maxLength('image_alt_name', 255)
            ->notEmptyString('image_alt_name', '画像名を入力してください。', function ($context) {
                if ($context['data']['image_flg']) {
                    return true;
                }
                return false;
            });

        return $validator;
    }
}
