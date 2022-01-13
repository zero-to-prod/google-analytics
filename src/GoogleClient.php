<?php

namespace ZeroToProd\GoogleClient;

use Exception;
use Google_Client;
use Google_Service_Analytics;
use Google_Service_Analytics_AccountSummaries;
use Google_Service_Analytics_Profile;
use Google_Service_Analytics_Webproperty;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Throwable;

class GoogleClient
{
    public Google_Service_Analytics $client;

    /**
     * @throws Exception
     */
    public function __construct(?string $gsa_key = null)
    {
        if ($client = self::getClient($gsa_key)) {
            $this->client = $client;
        } else {
            throw new RuntimeException('Invalid key.');
        }
    }

    /**
     * Returns a Google Service Analytics Client instance.
     *
     * @param  null  $path_name
     *
     * @see GsaTest::getClient()
     */
    public static function getClient(?string $gsa_key = null, $path_name = null): Google_Service_Analytics|bool
    {
        $path = $path_name ?? storage_path('app/analytics/service-account-credentials.json');

        File::ensureDirectoryExists(dirname($path));

        if ($gsa_key !== null) {
            File::put($path, $gsa_key);
        }

        try {
            $googleClient = new Google_Client();
            $googleClient->setApplicationName(config('gsa.app_name'));
            $googleClient->setAuthConfig($path);
            $googleClient->setScopes(config('gsa.scopes'));

            return new Google_Service_Analytics($googleClient);
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Checks if key is valid.
     *
     * @param  $gsa_key
     *
     * @return bool
     * @see GsaTest::keyIsValid()
     */
    public static function keyIsValid($gsa_key): bool
    {
        return self::getClient($gsa_key) !== false;
    }

    /**
     * Checks if key is invalid.
     *
     * @see GsaTest::keyIsInvalid()
     */
    public static function keyIsInvalid(?string $gsa_key): bool
    {
        return self::getClient($gsa_key) === false;
    }

    /**
     * An AccountSummary is a lightweight tree comprised of properties/profiles.
     * https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/management/accountSummaries.
     */
    public function accountSummariesList(): Google_Service_Analytics_AccountSummaries
    {
        return $this->client->management_accountSummaries->listManagementAccountSummaries();
    }

    /**
     * @param $accountId
     * @param $webPropertyId
     * @param $profileId
     * @param  array  $optParams
     *
     * @return \Google_Service_Analytics_Profile
     */
    public function profilesGet($accountId, $webPropertyId, $profileId, array $optParams = []): Google_Service_Analytics_Profile
    {
        return $this->client->management_profiles->get($accountId, $webPropertyId, $profileId, $optParams = []);
    }

    public function propertyGet(string $accountId, string $webPropertyId): Google_Service_Analytics_Webproperty
    {
        return $this->client->management_webproperties->get($accountId, $webPropertyId);
    }

    /**
     * @see GoogleAnalyticsHelperTest::configure()
     */
    public static function configure(int $profile_id, string $key_as_json): bool
    {
        Config::set('analytics.view_id', $profile_id);
        try {
            File::put(config('analytics.service_account_credentials_json'), $key_as_json);

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
