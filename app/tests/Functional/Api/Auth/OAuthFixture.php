<?php

declare(strict_types=1);

namespace Test\Functional\Api\Auth;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Model\Grant;
use Trikoder\Bundle\OAuth2Bundle\OAuth2Grants;
use User\Model\Username;
use User\Test\Builder\UserBuilder;

final class OAuthFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withUsername(new Username('oauth_user'))
            ->withHash('$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6') // password
            ->active()
            ->build();

        $manager->persist($user);

        $client = new Client('oauth', 'secret');
        $client->setGrants(new Grant(OAuth2Grants::PASSWORD));

        $manager->persist($client);

        $manager->flush();
    }
}
