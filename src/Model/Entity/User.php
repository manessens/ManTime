<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $idu
 * @property string $prenom
 * @property string $nom
 * @property string $email
 * @property string $mdp
 * @property int $actif
 * @property int $admin
 * @property int $prem_connect
 *
 * @property \App\Model\Entity\Article[] $articles
 */
class User extends Entity
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
        'prenom' => true,
        'nom' => true,
        'email' => false,
        'mdp' => true,
        'actif' => true,
        'prem_connect' => true,
        'admin' => true,
        'articles' => true
    ];

    protected function _setMdp($value)
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();

            return $hasher->hash($value);
        }
    }

}
