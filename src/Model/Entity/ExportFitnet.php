<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExportFitnet Entity
 *
 * @property int $id_fit
 * @property \Cake\I18n\FrozenTime $date_debut
 * @property \Cake\I18n\FrozenTime $date_fin
 * @property string $idc
 * @property string $idu
 * @property string $etat
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\User $users
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
        'etat' => true
    ];
}
