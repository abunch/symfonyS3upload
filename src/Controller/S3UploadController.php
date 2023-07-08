<?php
/**
 * @copyright: 2023 by Creekstone Technologies
 * All rights reserved.
 *
 * The contents of this file may not be copied, duplicated, or used without the
 * written consent of Creekstone Technologies.
 */

namespace App\Controller;

use App\Entity\Upload;
use App\Message\MoveToS3Storage\MoveToS3StorageMessage;
use App\Service\FileMoverService\FileMoverService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class S3UploadController
 */
class S3UploadController extends AbstractController
{

    public function __construct(
        private FileMoverService       $fileMoverService,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface    $bus
    ) {
    }

    /**
     * @Route("/upload-handler", name="dropzone_upload_controller")
     */
    public function upload(
        Request $request
    ) {
        /** @var UploadedFile $file */
        foreach ($request->files->all() as $file) {
            $md5File = md5_file($file->getRealPath());
            $upload  = new Upload();
            $upload->setSize($file->getSize())
                ->setMimeType($file->getClientMimeType())
                ->setMd5Hash($md5File)
                ->setFilename($file->getClientOriginalName())
                ->setExtension($file->getClientOriginalExtension());
            $this->entityManager->persist($upload);
            $this->entityManager->flush();
            if ($this->fileMoverService->moveToTemp($file->getRealPath(), $upload->getId())) {

                // MAKE/MODEL/YEAR/VIN/CUSTOMER_NUMBER/01/md5filename.jpg
                // $computedPathName = 'MAKE/MODEL/YEAR/VIN/CUSTOMER_NUMBER/01';
                $s3Filename = $upload->getId();

                $this->bus->dispatch(
                    new MoveToS3StorageMessage(
                        $this->fileMoverService->getTempDirLocation() . "/" . $upload->getId(),
                        $s3Filename
                    )
                );
            } else {
                $this->entityManager->remove($upload);
                $this->entityManager->flush();
            }
        }

        return new Response('', 200);
    }

    /**
     * @Route("/upload")
     */
    public function uploadForm()
    {
        return $this->render('s3-upload/upload.html.twig');
    }

}
