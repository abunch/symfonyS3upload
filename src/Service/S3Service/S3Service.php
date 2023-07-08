<?php

namespace App\Service\S3Service;

use AsyncAws\Core\Configuration;
use AsyncAws\SimpleS3\SimpleS3Client;
use Exception;

class S3Service
{

    // S3 Performance Tuning: https://aws.amazon.com/blogs/aws/amazon-s3-performance-tips-tricks-seattle-hiring-event/

    private SimpleS3Client $client;

    public function __construct(
        private string $s3Bucket,
        string         $s3AccessKey,
        string         $s3SecretKey,
        string         $s3Region = "us-west-2"
    ) {
        $this->client = new SimpleS3Client(
            Configuration::create([
                'region'          => $s3Region,
                'accessKeyId'     => $s3AccessKey,
                'accessKeySecret' => $s3SecretKey,
            ])
        );
    }

    public function delete(string $filename, bool $verify = false): bool
    {
        $this->client->deleteObject([
            'Bucket' => $this->s3Bucket,
            'Key'    => $filename,
        ]);

        if ($verify) {
            return $this->exists($filename);
        }

        return true;
    }

    public function exists(string $filename): bool
    {
        return $this->client->has($this->s3Bucket, $filename);
    }

    public function get(string $filename)
    {
        return $this->client->download($this->s3Bucket, $filename)->getContentAsResource();
    }

    public function save(string $filename, $contents): bool
    {
        try {
            $this->client->upload($this->s3Bucket, $filename, $contents);

            // $this->client->upload($this->s3Bucket, trim($filename, "/"), $contents);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
