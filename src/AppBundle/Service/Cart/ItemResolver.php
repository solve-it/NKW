<?php
namespace AppBundle\Service\Cart;

use AppBundle\Form\Type\OrderItemType;
use AppBundle\Entity\OrderItem;
use Sylius\Component\Cart\Model\CartItemInterface;
use Sylius\Component\Cart\Resolver\ItemResolverInterface;
use Sylius\Component\Cart\Resolver\ItemResolvingException;
use Doctrine\ORM\EntityManager;

class ItemResolver implements ItemResolverInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolve(CartItemInterface $item, $request)
    {
        $productId = $request->query->get('productId');
        $item = new OrderItem();
        // If no product id given, or product not found, we throw exception with nice message.
        if (!$productId || !$product = $this->getProductRepository()->find($productId)) {
            throw new ItemResolvingException('Requested product was not found. Report this to chuma@nimikiddies.com');
        }


        $this->isStockAvailable($product);

        // Assign the product to the item and define the unit price.
        $item->setProduct($product);
        $item->setUnitPrice($product->getPrice());


        // Everything went fine, return the item.
        return $item;
    }


    private function isStockAvailable($product)
    {
    }

    private function getProductRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Product');
    }
}