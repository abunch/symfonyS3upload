<?php
/**
 * @copyright: 2023 by Creekstone Technologies
 * All rights reserved.
 *
 * The contents of this file may not be copied, duplicated, or used without the
 * written consent of Creekstone Technologies.
 */

namespace App\MessageHandler\S3DeleteFile;

use App\Message\S3DeleteFile\S3DeleteFileMessage;
use App\Service\S3Service\S3Service;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class S3DeleteFileHandler
 */
class S3DeleteFileHandler implements MessageHandlerInterface
{

    public function __construct(
        private S3Service       $s3Service,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(
        S3DeleteFileMessage $event
    ) {
        if (!$this->s3Service->delete($event->targetFile)) {
            $this->logger->critical('Failed to delete file from S3', ['file' => $event->targetFile]);
        }
    }

}
