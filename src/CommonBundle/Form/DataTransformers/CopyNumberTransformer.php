<?php
namespace CommonBundle\Form\DataTransformers;

use AppBundle\Entity\Copy;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CopyNumberTransformer implements DataTransformerInterface
{
  private $manager;

  public function __construct(ObjectManager $manager)
  {
      $this->manager = $manager;
  }
    /**
     * Transforms an object (copy) to a string (number).
     *
     * @param  copy|null $copy
     * @return string
     */
    public function transform($copy)
    {
        if (null === $copy) {
            return '';
        }

        return $copy->getId();
    }

    /**
     * Transforms a string (number) to an object (copy).
     *
     * @param  string $copyNumber
     * @return copy|null
     * @throws TransformationFailedException if object (copy) is not found.
     */
    public function reverseTransform($copyNumber)
    {
        // no copy number? It's optional, so that's ok
        if (!$copyNumber) {
            return;
        }

        $copy = $this->manager
            ->getRepository('CommonBundle:Copy')
            // query for the copy with this id
            ->find($copyNumber)
        ;
        if (null === $copy) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An copy with number "%s" does not exist!',
                $copyNumber
            ));
        }

        return $copy;
    }
}
?>
