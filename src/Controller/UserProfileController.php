<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class UserProfileController
{
    #[Route('/profile', name: 'user_profile')]
    public function profile()
    {
        return $this->render('users/index.html.twig');
    }
}
