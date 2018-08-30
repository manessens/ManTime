<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    public function welcome($user)
    {
        // $this->to($user->email)
        $this->to('matthias.vincent@manessens.com')
            ->subject(sprintf('Bienvenu %s sur ManTime', $user->fullname))
            ->viewVars(['fullname'=>$user->fullname])
            ->emailFormat('html');
    }

    public function resetPassword($user)
    {
        $this
            ->to($user->email)
            ->subject('Réinitialisation du mot de passe ManTime')
            ->viewVars(['fullname'=>$user->fullname])
            ->emailFormat('html');
    }

}
