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
    /**
     * @throws Exception
     */
    public function __construct(public ?string $key = null, public ?Google_Service_Analytics $client = null)
    {
        $this->client = $client === null ? self::getClient($key) : $this->client;
    }

    /**
     * Returns a Google Service Analytics Client instance.
     */
    public function getClient(?string $gsa_key = null): Google_Service_Analytics|bool
    {
        $path = self::getKeyPath();

        self::storeKey($gsa_key, $path);

        try {
            $googleClient = new Google_Client();
            $googleClient->setApplicationName(config('app.app_name'));
            $googleClient->setAuthConfig($path);
            $googleClient->setScopes(config('google-client.scopes'));

            return new Google_Service_Analytics($googleClient);
        } catch (Throwable) {
            return throw new RuntimeException('Invalid key.');
        }
    }

    /**
     * Checks if key is valid.
     */
    public static function keyIsValid(string $key): bool
    {
        try {
            return (new GoogleClient($key))->client !== null;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Checks if key is invalid.
     */
    public static function keyIsInvalid(?string $gsa_key): bool
    {
        return !self::keyIsValid($gsa_key);
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
            File::put(config('google-client.service_account_credentials_json'), $key_as_json);

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    public static function getKeyPath(?string $path_name = null): string
    {
        $path = $path_name ?? config('google-client.service_account_credentials_json');

        File::ensureDirectoryExists(dirname($path));

        return $path;
    }

    /**
     * @param  string|null  $gsa_key
     * @param  string  $path
     *
     * @return void
     */
    public static function storeKey(?string $gsa_key, string $path): void
    {
        if ($gsa_key !== null) {
            File::put($path, $gsa_key);
        }
    }
}
