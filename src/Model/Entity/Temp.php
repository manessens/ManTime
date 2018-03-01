<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Temp Entity
 *
 * @property int $idt
 * @property \Cake\I18n\FrozenTime $date
 * @property float $time
 * @property int $n_ligne
 * @property int $lock
 * @property int $idu
 * @property int $idp
 * @property int $id_profil
 * @property int $ida
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Projet $projet
 * @property \App\Model\Entity\Profil $profil
 * @property \App\Model\Entity\Activitie $activitie
 */
class Temp extends Entity
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
        'idt' => true,
        'date' => true,
        'time' => true,
        'n_ligne' => true,
        'lock' => true,
        'idu' => true,
        'idp' => true,
        'id_profil' => true,
        'ida' => true,
        'user' => true,
        'projet' => true,
        'profil' => true,
        'activitie' => true
    ];
}
