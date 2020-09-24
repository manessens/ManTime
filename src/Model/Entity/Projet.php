<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Projet Entity
 *
 * @property int $idp
 * @property string $nom_projet
 * @property string $id_fit
 * @property int $idc
 * @property int $idm
 * @property int $idu
 * @property \Cake\I18n\FrozenTime $date_debut
 * @property \Cake\I18n\FrozenTime $date_fin
 * @property float $prix
 * @property string $type
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Activity[] $activities
 * @property \App\Model\Entity\Participant[] $participant
 * @property \App\Model\Entity\Matrice $matrice
 * @property \App\Model\Entity\Facturable $facturable
 * @property \App\Model\Entity\User $user
 */
class Projet extends Entity
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
        'nom_projet' => true,
        'id_fit' => true,
        'idc' => true,
        'date_debut' => true,
        'date_fin' => true,
        'client' => true,
        'activities' => true,
        'participant' => true,
        'idf' => true,
        'facturable' => true,
        'idm' => true,
        'matrice' => true,
        'prix' => true,
        'type' => true,
        'idu' => true,
        'user' => true
    ];

    protected function _getProjname()
    {
        return str_replace('_', '.', $this->_properties['nom_projet']);
    }
}
