<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{

    public function welcome($user)
    {
        // $this->to($user->email)
        $this->to($user->email)
            ->subject('Bienvenu sur ManTime')
            ->viewVars(['fullname'=>$user->prenom])
            ->emailFormat('html');
    }

    public function resetPassword($user)
    {
        $this->to($user->email)
            ->subject('Réinitialisation du mot de passe ManTime')
            ->viewVars(['fullname'=>$user->prenom])
            ->emailFormat('html');
    }

}
