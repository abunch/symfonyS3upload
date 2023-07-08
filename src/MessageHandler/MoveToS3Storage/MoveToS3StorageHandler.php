<?php
/**
 * @copyright: 2023 by Creekstone Technologies
 * All rights reserved.
 *
 * The contents of this file may not be copied, duplicated, or used without the
 * written consent of Creekstone Technologies.
 */

namespace App\MessageHandler\MoveToS3Storage;

use App\Message\MoveToS3Storage\MoveToS3StorageMessage;
use App\Service\S3Service\S3Service;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class MoveToS3StorageHandler
 */
class MoveToS3StorageHandler implements MessageHandlerInterface
{

    public function __construct(
        private S3Service       $s3Service,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(
        MoveToS3StorageMessage $event
    ) {
        if ($this->s3Service->save($event->destination, file_get_contents($event->source))) {
            $this->logger->info('Saved file to S3', ['fielanme' => $event->destination]);
        }

        if (!$this->s3Service->exists($event->destination)) {
            $this->logger->critical('Failed to save file to S3', ['fielanme' => $event->destination]);
        }

    }

}
