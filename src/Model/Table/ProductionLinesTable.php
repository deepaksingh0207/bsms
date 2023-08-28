<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductionLines Model
 *
 * @property \App\Model\Table\LineMastersTable&\Cake\ORM\Association\BelongsTo $LineMasters
 * @property \App\Model\Table\MaterialsTable&\Cake\ORM\Association\BelongsTo $Materials
 * @property \App\Model\Table\DailymonitorTable&\Cake\ORM\Association\HasMany $Dailymonitor
 *
 * @method \App\Model\Entity\ProductionLine newEmptyEntity()
 * @method \App\Model\Entity\ProductionLine newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ProductionLine[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductionLine get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductionLine findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ProductionLine patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductionLine[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductionLine|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductionLine saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductionLine[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ProductionLine[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ProductionLine[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ProductionLine[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ProductionLinesTable extends Table
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

        $this->setTable('production_lines');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('VendorFactories', [
            'foreignKey' => 'vendor_factory_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('LineMasters', [
            'foreignKey' => 'line_master_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Materials', [
            'foreignKey' => 'material_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Dailymonitor', [
            'foreignKey' => 'production_line_id',
        ]);
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
            ->scalar('sap_vendor_code')
            ->maxLength('sap_vendor_code', 10)
            ->requirePresence('sap_vendor_code', 'create')
            ->notEmptyString('sap_vendor_code');

        $validator
            ->integer('vendor_factory_id')
            ->notEmptyString('vendor_factory_id');

        $validator
            ->integer('line_master_id')
            ->notEmptyString('line_master_id');

        $validator
            ->integer('material_id')
            ->notEmptyString('material_id');

        $validator
            ->decimal('capacity')
            ->requirePresence('capacity', 'create')
            ->notEmptyString('capacity');

        $validator
            ->boolean('status')
            ->notEmptyString('status');

        $validator
            ->dateTime('added_date')
            ->allowEmptyDateTime('added_date');

        $validator
            ->dateTime('updated_date')
            ->allowEmptyDateTime('updated_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['sap_vendor_code', 'material_id', 'line_master_id', 'vendor_factory_id']), ['errorField' => 'sap_vendor_code']);
        $rules->add($rules->existsIn('vendor_factory_id', 'VendorFactories'), ['errorField' => 'vendor_factory_id']);
        $rules->add($rules->existsIn('line_master_id', 'LineMasters'), ['errorField' => 'line_master_id']);
        $rules->add($rules->existsIn('material_id', 'Materials'), ['errorField' => 'material_id']);

        return $rules;
    }
}
