<?php


namespace EmailBundle\Services;


use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class FosUserMailer implements MailerInterface
{

    /**
     * @var TwigMailer
     */
    private $twigMailer;
    /**
     * @var RouterInterface
     */
    private $router;


    public function __construct(TwigMailer $twigMailer, RouterInterface $router)
    {
        $this->twigMailer = $twigMailer;
        $this->router = $router;
    }


    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = 'email/confirm_email.html.twig';
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );

        $this->twigMailer->sendMessage($template, $context,  (string) $user->getEmail());
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = 'email/password_reset.html.twig';
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );

        $this->twigMailer->sendMessage($template, $context,  (string) $user->getEmail());
    }
}