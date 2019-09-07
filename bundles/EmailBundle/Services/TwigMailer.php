<?php


namespace EmailBundle\Services;


use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;

class TwigMailer implements TwigMailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    protected $twig;
    /**
     * @var string
     */
    private $fromEmail;


    /**
     * TwigSwiftMailer constructor.
     *
     * @param \Swift_Mailer         $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment     $twig
     * @param array                 $parameters
     */
    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router,  \Twig_Environment $twig, string $fromEmail)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;

        $this->fromEmail = $fromEmail;
    }
    /**
     * @param string $templateName
     * @param array  $parameters
     * @param array  $fromEmail
     * @param string $toEmail
     */
    public function sendMessage($templateName, $parameters,  $toEmail)
    {
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $parameters);
        $textBody = $template->renderBlock('body_text', $parameters);

        $htmlBody = '';

        if ($template->hasBlock('body_html', $parameters)) {
            $htmlBody = $template->renderBlock('body_html', $parameters);
        }

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($this->fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}