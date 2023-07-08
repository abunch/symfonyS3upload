<?php
/**
 * @copyright: 2023 by Creekstone Technologies
 * All rights reserved.
 *
 * The contents of this file may not be copied, duplicated, or used without the
 * written consent of Creekstone Technologies.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity()
 */
class Upload
{

    /**
     * @var \Ramsey\Uuid\UuidInterface|string $id
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private string $id;

    /**
     * @var string $extension
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private string $extension;

    /**
     * @var string $filename
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $filename;

    /**
     * @var string $md5Hash
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private string $md5Hash;

    /**
     * @var string $mimeType
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private string $mimeType;

    /**
     * @var int $size
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $size;

    /**
     * @var \DateTimeImmutable $uploaded
     * @ORM\Column(type="datetime_immutable", length=128, nullable=false)
     */
    private \DateTimeImmutable $uploaded;

    public function __construct()
    {
        $this->uploaded = new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     *
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getMd5Hash(): string
    {
        return $this->md5Hash;
    }

    /**
     * @param string $md5Hash
     *
     * @return $this
     */
    public function setMd5Hash($md5Hash)
    {
        $this->md5Hash = $md5Hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     *
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUploaded(): \DateTimeImmutable
    {
        return $this->uploaded;
    }

}
