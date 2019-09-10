<?php

namespace App\Security\Voter;

use App\Entity\Video;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class VideoVoter extends Voter
{
	/**
	 * @var Security
	 */
	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT'])
            && $subject instanceof Video;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
    	/** @var Video $subject */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
            	//check for author
                if ($subject->getAuthor() == $user) {
                	return true;
				}

				//check for role
                if ($this->security->isGranted('ROLE_ADMIN_VIDEO')) {
                	return true;
				}

				return false;
        }

        return false;
    }
}
