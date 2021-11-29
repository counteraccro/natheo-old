<?php

namespace App\Security;

use App\Entity\User;
use App\Service\Admin\System\AccessService;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\UserService;
use App\Twig\Admin\OptionTwig;
use Doctrine\Persistence\ManagerRegistry as Doctrine;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
//use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'front_security_app_login';

    private UrlGeneratorInterface $urlGenerator;

    private OptionService $optionService;

    private UserService $userService;

    public function __construct(UrlGeneratorInterface $urlGenerator, OptionService $optionService, UserService $userService)
    {
        $this->urlGenerator = $urlGenerator;
        $this->optionService = $optionService;
        $this->userService = $userService;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $theme = $this->optionService->getOptionByKey(OptionService::GO_ADM_THEME_ADMIN, OptionService::GO_ADM_THEME_ADMIN_DEFAULT_VALUE, true);
        $request->getSession()->set(OptionService::KEY_SESSION_THEME_ADMIN, $theme);

        $default_local = $this->optionService->getOptionByKey(OptionService::GO_ADM_GLOBAL_LANGUE, OptionService::GO_ADM_GLOBAL_LANGUE_DEFAULT_VALUE, true);

        /** @var User $user */
        $user = $token->getUser();

        // Mise à jour des droits
        $tabRouteAccess = [];
        foreach ($user->getRolesCms() as $rolesCm) {

            foreach ($rolesCm->getRouteRights() as $routeRight) {
                $tabRouteAccess[] = $routeRight->getRoute()->getRoute();
            }
        }
        $request->getSession()->set(AccessService::KEY_SESSION_LISTE_ROUTE_ACCESS, $tabRouteAccess);

        $this->userService->updateLastLogin($user);

        /** Cas si le user est désactivé, on le déconnecte de force */
        if($user->getIsDisabled())
        {
            return new RedirectResponse($this->urlGenerator->generate('front_security_app_logout', ['_locale' => $default_local]));
        }

        /*if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }*/

        return new RedirectResponse($this->urlGenerator->generate('admin_dashboard_index', ['_locale' => $default_local]));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
