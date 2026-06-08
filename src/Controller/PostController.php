<?php

/**
 * Post controller.
 */

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Service\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PostController.
 */
#[Route('/post')]
class PostController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param PostServiceInterface $postService Post service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(private readonly PostServiceInterface $postService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * @param int $page Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'post_index', methods: ['GET'])]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        return $this->render('post/index.html.twig', [
            'pagination' => $this->postService->getPaginatedList($page),
        ]);
    }

    /**
     * View action.
     *
     * @param Post $post Post entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id<\d+>}', name: 'post_view', methods: ['GET'])]
    public function view(Post $post): Response
    {
        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'post_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post, [
            'method' => 'POST',
            'action' => $this->generateUrl('post_edit', ['id' => $post->getId()]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('post_view', ['id' => $post->getId()]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Post $post Post entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'post_delete', methods: ['POST'])]
    public function delete(Post $post): Response
    {
        $this->postService->delete($post);

        $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));

        return $this->redirectToRoute('post_index');
    }
}
