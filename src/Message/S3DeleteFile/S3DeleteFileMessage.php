<?php
/**
 * @copyright: 2023 by Creekstone Technologies
 * All rights reserved.
 *
 * The contents of this file may not be copied, duplicated, or used without the
 * written consent of Creekstone Technologies.
 */

namespace App\Message\S3DeleteFile;

/**
 * Class S3DeleteFileMessage
 */
class S3DeleteFileMessage
{

    public function __construct(
        public string $targetFile
    ) {
    }

}
