<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExportFitnet Entity
 *
 * @property int $id_fit
 * @property \Cake\I18n\FrozenTime $date_debut
 * @property \Cake\I18n\FrozenTime $date_fin
 * @property int $idc
 * @property int $idu
 * @property string $etat
 * @property \Cake\I18n\FrozenTime $date_create
 * @property \Cake\I18n\FrozenTime $date_update
 */
class ExportFitnet extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'date_debut' => true,
        'date_fin' => true,
        'idc' => true,
        'idu' => true,
        'etat' => true,
        'date_create' => true,
        'date_update' => true
    ];
}
