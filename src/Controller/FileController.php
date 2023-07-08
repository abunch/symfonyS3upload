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
use App\Message\S3DeleteFile\S3DeleteFileMessage;
use App\Service\S3Service\S3Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileController
 * @Route("/file")
 */
class FileController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface    $bus,
        private S3Service $s3Service
    ) {
    }

    /**
     * @Route("/delete/{upload}")
     */
    public function delete(
        Upload  $upload,
        Request $request
    ) {
        $this->bus->dispatch(new S3DeleteFileMessage($upload->getId()));

        $this->entityManager->remove($upload);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/download/{upload}")
     */
    public function download(
        Upload  $upload
    ) {
        $response = new StreamedResponse(function() use ($upload) {
            $outputStream = fopen('php://output', 'wb');
            $fileStream   = $this->s3Service->get($upload->getId());
            stream_copy_to_stream($fileStream, $outputStream);
        });
        $response->headers->set('Content-Type', $upload->getMimeType());

        return $response;
    }

    /**
     * @Route("/save/{upload}")
     */
    public function save(
        Upload $upload
    ) {
        $response = new StreamedResponse(function() use ($upload) {
            $outputStream = fopen('php://output', 'wb');
            $fileStream   = $this->s3Service->get($upload->getId());
            stream_copy_to_stream($fileStream, $outputStream);
        });
        $response->headers->set('Content-Type', $upload->getMimeType());
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $upload->getFilename() . "." . $upload->getExtension()
        );
        $response->headers->set('Content-Disposition', $disposition);


        return $response;
    }

    /**
     * @Route("/delete-all")
     */
    public function deleteAll(
        Request $request
    ) {
        foreach ($this->entityManager->getRepository(Upload::class)->findAll() as $upload) {
            $this->bus->dispatch(new S3DeleteFileMessage($upload->getId()));

            $this->entityManager->remove($upload);
            $this->entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("")
     */
    public function list()
    {
        $data['data'] = [
            'files' => $this->entityManager->getRepository(Upload::class)->findBy([], [
                'filename' => 'ASC',
            ]),
        ];

        return $this->render('file/list.html.twig', $data);

    }

}
