<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;

class UpdateApnCertificateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-apn-certificate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新APN证书';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $newExtension = '.tmp';
        $oldExtension = '.bak';
        $certificateDir = base_path('docker/push/');
        $appP12Path = $certificateDir. 'c.p12';
        $clipP12Path = $certificateDir. 'cc.p12';
        $appPemPath = $certificateDir. 'c.pem';
        $clipPemPath = $certificateDir. 'cc.pem';
        $password = env('APN_CERTIFICATE_SECRET');

        $appP12NewPath = $appP12Path . $newExtension;
        $clipP12NewPath = $clipP12Path . $newExtension;

        $this->loadCertificate($certificateDir, $newExtension);

        if (md5_file($appP12Path) != md5_file($appP12NewPath)) {
            rename($appP12Path, $appP12Path . $oldExtension);
            rename($appP12NewPath, $appP12Path);
            exec(sprintf('openssl pkcs12 -in %s -passin pass:%s -out %s -passout pass:%s',
                $appP12Path,
                $password,
                $appPemPath,
                $password,
            ));
        }

        if (md5_file($clipP12Path) != md5_file($clipP12NewPath)) {
            rename($clipP12Path, $clipP12Path . $oldExtension);
            rename($clipP12NewPath, $clipP12Path);
            exec(sprintf('openssl pkcs12 -in %s -passin pass:%s -out %s -passout pass:%s',
                $clipP12Path,
                $password,
                $clipPemPath,
                $password,
            ));
        }

    }

    /**
     * @param string $certificateDir
     * @param string $extension
     * @return void
     */
    public function loadCertificate(string $certificateDir, string $extension): void
    {
        $certificateUrls = [
            'easychen/pushdeer/main/push/c.p12',
            'easychen/pushdeer/main/push/cc.p12',
        ];

        $client = new Client(['base_uri' => 'https://raw.githubusercontent.com/']);
        $requests = function () use ($client, $certificateUrls, $certificateDir, $extension) {
            foreach ($certificateUrls as $uri) {
                $filePath = $certificateDir . pathinfo($uri, PATHINFO_BASENAME) . $extension;
                $requestOptions = [
                    RequestOptions::SINK => $filePath,
                ];
                yield function () use ($client, $uri, $requestOptions) {
                    return $client->getAsync($uri, $requestOptions);
                };
            }
        };

        $pool = new Pool($client, $requests()); // concurrency default 25
        $promise = $pool->promise();
        $promise->wait();
    }
}
