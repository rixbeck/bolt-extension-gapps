<?php
/**
 * @author Rix Beck <rix@neologik.hu>
 */

namespace Bolt\Extension\Rixbeck\Gapps\Service;

interface AccountsAwareInterface
{
    public function authenticate(\Google_Auth_AssertionCredentials $cred);

    public function createCredentialsFor($scopeNames);
}
