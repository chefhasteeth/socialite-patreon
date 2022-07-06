<?php

namespace Chefhasteeth\Socialite\Patreon;

use SocialiteProviders\Manager\SocialiteWasCalled;

class PatreonExtendSocialite
{
    /**
     * Register the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('patreon', Provider::class);
    }
}
