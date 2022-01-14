<?php

namespace ZeroToProd\GoogleClient\Tests;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use ZeroToProd\GoogleClient\GoogleClientServiceProvider;

class TestCase extends Orchestra
{
    protected string $key = '{
                  "type": "service_account",
                  "project_id": "bogus-project",
                  "private_key_id": "ebdd3510df6b11a57cd335d3e4824bd4799095eb",
                  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC6nJAcG8TDq5d9\niTUk3FJFfiXE1bxCXYJFSquLGS3gRM8q5+lufS5RBOxmgSAolgef4DAAw2R4FyVD\nanZ8ow8IRzqO7ebvtBiQ+98SoWd2/TR5qjayA+wJU+vOf6riMHDhYV3SvnI8W1D0\n/Ab28LD2/RnQnvk70i+mvF1+z91I2cDUHIA6LSJJbF5mm87Oc04I56+bh5CCNHdn\nx4xgXjKitt9yWHUJ+TVjGJko2uWXrq2MfMmJhWu/hr6aPuSOv1IlR7sv+qVDlI6u\nBXROvKTFx258ocSgBCcP0q2wSC0Ji5uy0fD1oM50OsuCStBaOu8U0jWWgGd5c8rj\nX/Of8JRJAgMBAAECggEAC2uJZi73hldPQ193i8nv6aYj/ufASE9wqXeTB/AtM+Gs\ny/NlVbL+IqHVKxtmhxVSDMwAzrXRGt2lFNBvGobgD0xMnnKbWhAAxjP5HYAYf4T/\nW8gO5itXRpAxiMbyA5BIrYJH5khsCprhWXIS+wm/yrMH4WcuhCVYxTDB3VYwie9Z\nT7AzjYpLPzsEQBU7x011fQEwJPl9HgVtc/cLnD98cSFW5tRswhesMvXqlag1XJ46\ncySsQcaWAi5H301sPyLWtyKVe3sHUF/SBDh619d92nb0C+xbCvYpcZ7pG0IqRBc1\niFOxy6EXtL37GDLFEXb0qUfsWev2O1+Os949wA0UAQKBgQDxu1XFlqy6RmuJgNXT\nmVCRrvSIl5wS+N9XiGJTj1YUdo+MgZ4XGm2fQCoRU61fFMs7QLCc7h292vrZGDui\nggRmhp2LNIgrwqDQBuGmG4Gvz+egIElvn+Rb+7H5HKuCD7YYNM2VKuNuNIEaX94y\nXIOQcSNueiplLK9OLoutdypQ8wKBgQDFoFbc00xw74okMGbtIXtzg2Vgfon+wlyP\n9Dbzf/ul9oILnEKmB8XohYUnxlx7T0FLD0H/8KuboYyd1RQ5E+/+Yy13mFnJHcX5\noQXoZHFm4M0tfwrzgxxywIfZtvpFz4bxI45EAV0pv7PqRZ/a/1TvpUMvKcNGmP5l\nreAV7Ye00wKBgQC26lGp9Sz4u0auQr4Kos2zn1bMcSSZupaODtaQcjrpuxk+cepl\ngwsGYTAz8kSTWRG74WSDKy94NVZxpgOiIY4g4qYd6v9LVbgIEdz3q4LDvaeApE3N\nWIkdDWULoqUpAqiAwpv5zv1PrA0xHu/s3dJ0Hxh3hZvE+T4iA9iPx0dO/wKBgGbT\nRT7Chgxg5hxsPrF08Vmp7S5myIAQOr7/zzOkzJeOih1mYVsnwjZ4ek62q9nQ4+Uc\n/Dchzvg79wEMI+iK0h0nFzoZv+Wfoyl+Oaev0z03DCp25ojLGBswx75ksRVvpAOF\nf3ksyH93ajIjpcf5gATslOdOMHdkFUFsVaKzIx/XAoGBAITrWqv8GjcekG98+PYz\nr0rLzLEkvsd+CFeepZvrv2SsfS9QNzbcn5CLa5Dnxbz0QNe6qWcEgdCPDFryF71m\nIke7MmwHuFSVs6DgKjtakiy4pPkPw90EgxPcQkgeSileND+JuGzUks+vKoelQW3s\nSbi6ivaN6mxbl1IKWNZT6RE2\n-----END PRIVATE KEY-----\n",
                  "client_email": "mgid-351@bogus.iam.gserviceaccount.com",
                  "client_id": "111330055981083244103",
                  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
                  "token_uri": "https://oauth2.googleapis.com/token",
                  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
                  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/mgid-351%40arrowway.iam.gserviceaccount.com"
                }';

    protected function setUp(): void
    {
        parent::setUp();
        File::delete(config('google-client.service_account_credentials_json'));
    }

    protected function getPackageProviders($app)
    {
        return [
            GoogleClientServiceProvider::class,
        ];
    }

    /**
     * @return void
     */
    public function storeKeyForTest(): void
    {
        $path = config('google-client.service_account_credentials_json');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->key);
    }
}
