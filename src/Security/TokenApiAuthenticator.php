<?php

namespace App\Security;

use App\Repository\TokenApiRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenApiAuthenticator extends AbstractGuardAuthenticator
{
	/**
	 * @var TokenApiRepository
	 */
	private $tokenApiRepository;

	public function __construct(TokenApiRepository $tokenApiRepository)
	{
		$this->tokenApiRepository = $tokenApiRepository;
	}

	public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
			&& 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');

        return substr($authorizationHeader, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->tokenApiRepository->findOneBy([
        	'token' => $credentials
		]);

        if (!$token) {
        	throw new CustomUserMessageAuthenticationException('Invalid Token');
		}

		if ($token->isTokenExpired()) {
			throw new CustomUserMessageAuthenticationException('The token has expired!');
		}

		return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
    	return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
		return new JsonResponse([
			'message' => $exception->getMessage()
		], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
