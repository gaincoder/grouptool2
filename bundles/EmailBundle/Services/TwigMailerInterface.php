<?php

namespace EmailBundle\Services;

interface TwigMailerInterface
{
    /**
     * @param string $templateName
     * @param array $parameters
     * @param array $fromEmail
     * @param string $toEmail
     */
    public function sendMessage($templateName, $parameters, $toEmail);
}