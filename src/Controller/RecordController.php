<?php
namespace App\Controller;

use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/record')]
class RecordController extends AbstractController
{
    // ---------------------------------------------------
    // Index action - lista wszystkich rekordów
    // ---------------------------------------------------
    #[Route(name: 'record_index', methods: ['GET'])]
    public function index(RecordRepository $repository): Response
    {
        $records = $repository->findAll();

        return $this->render('record/index.html.twig', [
            'records' => $records
        ]);
    }

    // ---------------------------------------------------
    // View action - szczegóły pojedynczego rekordu
    // ---------------------------------------------------
    #[Route(
        '/{id}',
        name: 'record_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET']
    )]
    public function view(RecordRepository $repository, int $id): Response
    {
        $record = $repository->findOneById($id);

        if (null === $record) {
            throw $this->createNotFoundException('Record not found');
        }

        return $this->render('record/view.html.twig', [
            'record' => $record
        ]);
    }

    // ---------------------------------------------------
    // Bookmarks action - przykład użycia bookmarks.inc.php
    // ---------------------------------------------------
    #[Route('/bookmarks', name: 'record_bookmarks', methods: ['GET'])]
    public function bookmarks(): Response
    {
        // Wczytanie pliku inc/debug.inc.php jeśli potrzebne
        require_once __DIR__ . '/../../inc/debug.inc.php';
        require_once __DIR__ . '/../../inc/bookmarks.inc.php';

        $allBookmarks = find_all();

        return $this->render('record/bookmarks.html.twig', [
            'bookmarks' => $allBookmarks
        ]);
    }
}
