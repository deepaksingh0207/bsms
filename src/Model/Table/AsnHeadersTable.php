<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AsnHeaders Model
 *
 * @property \App\Model\Table\VendorFactoriesTable&\Cake\ORM\Association\BelongsTo $VendorFactories
 * @property \App\Model\Table\PoHeadersTable&\Cake\ORM\Association\BelongsTo $PoHeaders
 * @property \App\Model\Table\AsnFootersTable&\Cake\ORM\Association\HasMany $AsnFooters
 *
 * @method \App\Model\Entity\AsnHeader newEmptyEntity()
 * @method \App\Model\Entity\AsnHeader newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AsnHeader[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AsnHeader get($primaryKey, $options = [])
 * @method \App\Model\Entity\AsnHeader findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AsnHeader patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AsnHeader[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AsnHeader|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AsnHeader saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AsnHeader[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AsnHeader[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AsnHeader[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AsnHeader[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AsnHeadersTable extends Table
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

        $this->setTable('asn_headers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('VendorFactories', [
            'foreignKey' => 'vendor_factory_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('PoHeaders', [
            'foreignKey' => 'po_header_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('AsnFooters', [
            'foreignKey' => 'asn_header_id',
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
            ->integer('vendor_factory_id')
            ->notEmptyString('vendor_factory_id');

        $validator
            ->integer('po_header_id')
            ->notEmptyString('po_header_id');

        $validator
            ->scalar('asn_no')
            ->maxLength('asn_no', 15)
            ->requirePresence('asn_no', 'create')
            ->notEmptyString('asn_no')
            ->add('asn_no', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('invoice_path', 'create')
            ->notEmptyString('invoice_path');

        $validator
            ->scalar('invoice_no')
            ->maxLength('invoice_no', 15, 'Invoice No need to be max 15 characters long')
            ->requirePresence('invoice_no', 'create')
            ->notEmptyString('invoice_no');

        $validator
            ->date('invoice_date')
            ->allowEmptyDate('invoice_date');

        $validator
            ->decimal('invoice_value')
            ->requirePresence('invoice_value', 'create')
            ->notEmptyString('invoice_value');

        $validator
            ->scalar('vehicle_no')
            ->maxLength('vehicle_no', 12, 'Vehicle No need to be max 12 characters long')
            ->requirePresence('vehicle_no', 'create')
            ->notEmptyString('vehicle_no');

        $validator
            ->scalar('driver_name')
            ->maxLength('driver_name', 15, 'Driver Name need to be max 15 characters long')
            ->requirePresence('driver_name', 'create')
            ->notEmptyString('driver_name');

        $validator
            ->scalar('driver_contact')
            ->maxLength('driver_contact', 15)
            ->requirePresence('driver_contact', 'create')
            ->notEmptyString('driver_contact');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        $validator
            ->dateTime('added_date')
            ->notEmptyDateTime('added_date');

        $validator
            ->dateTime('updated_date')
            ->notEmptyDateTime('updated_date');

        $validator
            ->dateTime('gateout_date')
            ->allowEmptyDateTime('gateout_date');

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
        $rules->add($rules->isUnique(['asn_no']), ['errorField' => 'asn_no']);
        $rules->add($rules->existsIn('vendor_factory_id', 'VendorFactories'), ['errorField' => 'vendor_factory_id']);
        $rules->add($rules->existsIn('po_header_id', 'PoHeaders'), ['errorField' => 'po_header_id']);

        return $rules;
    }
}
