<?php

namespace App\Security;

use App\Service\Admin\UserMapper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Entity\AppException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
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
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator
    ) {
        $this->userMapper = $userMapper;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
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

    public function getCredentials(Request $request)
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

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
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

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        
        // redirect
        return new RedirectResponse($this->router->generate('main_index'));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }
}
