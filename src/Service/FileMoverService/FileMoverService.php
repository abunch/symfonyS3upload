<?php
/**
 * @copyright: 2023 by Creekstone Technologies
 * All rights reserved.
 *
 * The contents of this file may not be copied, duplicated, or used without the
 * written consent of Creekstone Technologies.
 */

namespace App\Service\FileMoverService;

/**
 * Class FileMoverService
 */
class FileMoverService
{

    public function __construct(
        private string $targetTempDir
    ) {
    }

    public function getTempDirLocation(): string
    {
        return $this->targetTempDir;
    }

    public function moveToTemp(string $source, string $targetName): bool
    {
        if (!is_dir($source)) {
            if (!is_dir($this->targetTempDir)) {
                mkdir($this->targetTempDir);
            }

            return rename($source, $this->targetTempDir . "/" . $targetName);
        }

        return false;
    }

}
