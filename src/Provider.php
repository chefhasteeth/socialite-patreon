<?php

namespace Chefhasteeth\Socialite\Patreon;

use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'PATREON';

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['identity', 'identity[email]', 'identity.memberships'];

    /**
     * {@inherticdoc}.
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://www.patreon.com/oauth2/authorize',
            $state,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.patreon.com/oauth2/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://www.patreon.com/api/oauth2/v2/identity',
            [
                'query' => [
                    'include' => 'memberships.campaign',
                    'fields' => [
                        'user' => 'email,full_name,image_url,vanity',
                        'member' => 'campaign_lifetime_support_cents,currently_entitled_amount_cents,pledge_cadence,pledge_relationship_start,patron_status,is_follower',
                    ],
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ],
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user): User
    {
        return (new User())
            ->setRaw($user)
            ->map([
                'id' => $user['data']['id'],
                'nickname' => Arr::get($user, 'data.attributes.vanity', Arr::get($user, 'data.attributes.full_name')),
                'name' => Arr::get($user, 'data.attributes.full_name'),
                'email' => Arr::get($user, 'data.attributes.email'),
                'avatar' => Arr::get($user, 'data.attributes.image_url'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}

