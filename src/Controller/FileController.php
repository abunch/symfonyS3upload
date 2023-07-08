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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        private MessageBusInterface    $bus
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
