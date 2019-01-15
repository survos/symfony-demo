<?php

namespace App\Request\ParamConverter;

use App\Entity\Post;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostParamConverter implements ParamConverterInterface
{

    /**
     * @var ManagerRegistry $registry Manager registry
     */
    private $registry;


    /**
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry = null)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     *
     * Check, if object supported by our converter
     */
    public function supports(ParamConverter $configuration)
    {
        return Post::class == $configuration->getClass();
    }

    /**
     * {@inheritdoc}
     *
     * Applies converting
     *
     * @throws \InvalidArgumentException When route attributes are missing
     * @throws NotFoundHttpException     When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $postSlug = $request->attributes->get('postSlug');
//         dump($request->attributes);

        // Check, if route attributes exists
        if (null === $postSlug ) {
            throw new \InvalidArgumentException('Route attribute postSlug is missing');
        }

        // Get actual entity manager for class
        $em = $this->registry->getManagerForClass($configuration->getClass());

        $repository = $em->getRepository($configuration->getClass());

        // Try to find project by its slug
        $post = $repository->findOneBy(['slug' => $postSlug]);

        if (null === $post || !($post instanceof Post)) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $configuration->getClass()));
        }

        // Map found project to the route's parameter
        $request->attributes->set($configuration->getName(), $post);
    }

}
