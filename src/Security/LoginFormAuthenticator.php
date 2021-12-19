<?php

namespace App\Security;

use App\Service\Admin\UserMapper;
use App\Entity\Admin\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use App\Entity\AppException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private $userMapper;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $validator;

    public function __construct(
        UserMapper $userMapper,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordHasherInterface $passwordEncoder,
        ValidatorInterface $validator
    ) {
        $this->userMapper = $userMapper;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }
        
    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);
        $user = $this->getUser($credentials);
        
        return new Passport(new UserBadge($user->getUsername()), new PasswordCredentials($credentials['password']));
    }
    
    private function validate(Request $request): array
    {
        $errorsUser = $this->validator->validate(
            $request->request->get('username'),
            array(
                                    new NotBlank(['message' => 'Username can not be empty']),
                                    new Length(['max' => 25,
                                                'maxMessage' => 'Username should have 25 characters or less.']),
                                    new Regex(['pattern' => "/[^A-Za-z0-9_]/",
                                                'match' => false,
                                                'message' => "Username contain invalid characters"])
                                )
        );
        
        $errorsPass = $this->validator->validate(
            $request->request->get('password'),
            array(
                                    new NotBlank(['message' => 'Password can not be empty']),
                                    new Length(['max' => 200,
                                                'maxMessage' => 'Password should have 200 characters or less.'])
                                )
        );
        
        return array(
            'uErr' => $errorsUser,
            'pErr' => $errorsPass
        );
    }

    private function getCredentials(Request $request)
    {
        // Check data
        $err = $this->validate($request);
        
        if (count($err['uErr']) > 0) {
            throw new CustomUserMessageAuthenticationException($err['uErr'][0]->getMessage());
        }
        if (count($err['pErr']) > 0) {
            throw new CustomUserMessageAuthenticationException($err['pErr'][0]->getMessage());
        }
        
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    private function getUser($credentials): User
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new CustomUserMessageAuthenticationException('Invalid CSFR');
        }
        
        try {
            $user = $this->userMapper->getUserByName($credentials['username']);
        } catch (AppException $ex) {
            if ($ex->getCode() == AppException::USER_NOT_EXIST) {
                throw new CustomUserMessageAuthenticationException('Username could not be found.');
            } else {
                throw new CustomUserMessageAuthenticationException($ex->getMessage());
            }
        }
        
        // User is activated?
        if (!$user->isActive()) {
            throw new CustomUserMessageAuthenticationException('User is inactive.');
        }

        return $user;
    }

    private function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        
        // redirect
        return new RedirectResponse($this->router->generate('main_index'));
    }
    
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
}
