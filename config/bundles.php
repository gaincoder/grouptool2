<?php

use EmailBundle\EmailBundle;

$bundles = [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class => ['all' => true],
    FOS\UserBundle\FOSUserBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle::class => ['all' => true],
    Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class => ['all' => true],
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => ['all' => true],
];

//if((bool)getenv('DISABLE_BIRTHDAY_BUNDLE') === false) {
//    $bundles[\BirthdayBundle\BirthdayBundle::class] = ['all' => true];
//}

if((bool)getenv('DISABLE_PHOTOALBUM_BUNDLE') === false) {
    $bundles[\PhotoalbumBundle\PhotoalbumBundle::class] = ['all' => true];
}

if((bool)getenv('DISABLE_EVENT_BUNDLE') === false) {
    $bundles[\EventBundle\EventBundle::class] = ['all' => true];
}

if((bool)getenv('DISABLE_INFO_BUNDLE') === false) {
    $bundles[\InfoBundle\InfoBundle::class] = ['all' => true];
}

if((bool)getenv('DISABLE_NEWS_BUNDLE') === false) {
    $bundles[\NewsBundle\NewsBundle::class] = ['all' => true];
}

if((bool)getenv('DISABLE_POLL_BUNDLE') === false) {
    $bundles[\PollBundle\PollBundle::class] = ['all' => true];
}

if((bool)getenv('DISABLE_COMPANY_BUNDLE') === false) {
    $bundles[\CompanyBundle\CompanyBundle::class] = ['all' => true];
}
//if((bool)getenv('DISABLE_TELEGRAM_BUNDLE') === false) {
//    $bundles[\TelegramBundle\TelegramBundle::class] = ['all' => true];
//}

if((bool)getenv('DISABLE_EMAIL_BUNDLE') === false) {
    $bundles[EmailBundle::class] = ['all' => true];
}

return $bundles;
