<?php
/**
 * @copyright: 2023 by Creekstone Technologies
 * All rights reserved.
 *
 * The contents of this file may not be copied, duplicated, or used without the
 * written consent of Creekstone Technologies.
 */

namespace App\Message\MoveToS3Storage;

/**
 * Class MoveToS3StorageMessage
 */
class MoveToS3StorageMessage
{

    public function __construct(
        public string $source,
        public string $destination
    ) {
    }

}
