<?php

/**
 * Post controller.
 */

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\CommentRepository;
use App\Service\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
     * @param PostServiceInterface $postService       Post service
     * @param TranslatorInterface  $translator        Translator
     * @param CommentRepository    $commentRepository Comment repository
     */
    public function __construct(private readonly PostServiceInterface $postService, private readonly TranslatorInterface $translator, private readonly CommentRepository $commentRepository)
    {
    }

    /**
     * Index action.
     *
     * @param int $page Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'post_index', methods: 'GET')]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->postService->getPaginatedList($page);

        return $this->render('post/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * View action.
     *
     * @param int $id Id
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'post_view', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function view(int $id): Response
    {
        $post = $this->postService->findOneById($id);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $comments = $this->commentRepository->findByPost($post);

        return $this->render(
            'post/view.html.twig',
            [
                'post' => $post,
                'comments' => $comments,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'post_create', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'post_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(
            PostType::class,
            $post,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('post_edit', ['id' => $post->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'post_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Post $post): Response
    {
        $form = $this->createForm(FormType::class, $post, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('post_delete', ['id' => $post->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->delete($post);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/delete.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }
}
