<?php


namespace App\Service;


use App\Entity\Category;
use App\Entity\Item;
use App\Entity\Menu;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\MenuRepository;
use App\SDK\UberEats\MenuSDK;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class MenuUberEatsService
{
    /**
     * @var MenuRepository
     */
    private MenuRepository $menuRepository;
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;
    /**
     * @var ItemRepository
     */
    private ItemRepository $itemRepository;
    /**
     * @var MenuSDK
     */
    private MenuSDK $menuSDK;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(MenuSDK $menuSDK, MenuRepository $menuRepository, CategoryRepository $categoryRepository, ItemRepository $itemRepository, EntityManagerInterface $entityManager)
    {
        $this->menuRepository = $menuRepository;
        $this->categoryRepository = $categoryRepository;
        $this->itemRepository = $itemRepository;
        $this->menuSDK = $menuSDK;
        $this->entityManager = $entityManager;
    }

    public function upload(string $storeID)
    {
        $this->menuSDK->uploadMenu($storeID, [
            'menus' => $this->getMenus(),
            'categories' => $this->getCategories(),
            'items' => $this->getItems(),
            'menu_type' => 'MENU_TYPE_FULFILLMENT_DELIVERY'
        ]);
    }

    public function fetch(string $storeID)
    {
        $menu = $this->menuSDK->getMenus($storeID);
        $returned = $this->createCategories($menu['categories']);
        $this->createItems($menu['items'], $returned[1]);
        $this->createMenus($menu['menus'], $returned[0]);
    }

    public function createItems(array $items, array $idToAdd): void
    {
        array_map(function (array $item) use ($idToAdd){
            $createdItem = new Item();
            $createdItem->setTitle($item['title']);
            $createdItem->setPriceInfo($item['price_info']);
            $createdItem->setCategory($this->categoryRepository->find($idToAdd[$item['id']]));

            $this->entityManager->persist($createdItem);
        }, $items);

        $this->entityManager->flush();
    }

    public function createCategories(array $categories): array
    {
        $convertedIDs = [];
        $idToAdd = [];
        array_map(function (array $category) use (&$idToAdd, &$convertedIDs) {
            $createdCategory = new Category();
            $createdCategory->setTitle($category['title']);
            $createdCategory->setSubtitle($category['title']);

            $this->entityManager->persist($createdCategory);
            $convertedIDs[$category['id']] = $createdCategory->getId();
            foreach ($category['entities'] as $entity)
                $idToAdd[$entity['id']] = $createdCategory->getId();
        }, $categories);

        $this->entityManager->flush();

        return [$convertedIDs, $idToAdd];
    }

    public function createMenus(array $menus, array $convertedId): void
    {
        array_map(function (array $menu) use ($convertedId) {
            $createdMenu = new Menu();
            $createdMenu->setTitle($menu['title']);

            foreach ($menu['category_ids'] as $entity)
                $createdMenu->addCategory($this->categoryRepository->find($convertedId[$entity]));

            $this->entityManager->persist($createdMenu);
        }, $menus);

        $this->entityManager->flush();
    }

    protected function getNecessaryAttributesFromArray(array $item, array $attributes): array
    {
        $toReturn = [];
        foreach ($attributes as $attribute)
            $toReturn[$attribute] = $item[$attribute];

        return $toReturn;
    }

    protected function getNecessaryAttributes($entity, array $attributes): array
    {
        $toReturn = [];
        foreach ($attributes as $attribute) {
            $getFunctionName = (new CamelCaseToSnakeCaseNameConverter())->denormalize('get_' . $attribute);
            $toReturn[$attribute] = $entity->$getFunctionName();
        }

        return $toReturn;
    }

    protected function getMenus(): array
    {
        $menus = $this->menuRepository->findAll();

        return array_map(function (Menu $menu) {
            $categoriesID = array_map(function (Category $category) {
                return (string) $category->getId();
            }, $menu->getCategories()->toArray());

            return array_merge($this->getNecessaryAttributes($menu, ['id', 'title']), ['category_ids' => $categoriesID]);
        }, $menus);
    }

    protected function getCategories(): array
    {
        $categories = $this->categoryRepository->findAll();

        return array_map(function (Category $category) {
            $entities = array_map(function (Item $item) {
                return ['id' => (string) $item->getId(), 'type' => 'ITEM'];
            }, $category->getItems()->toArray());

            return array_merge($this->getNecessaryAttributes($category, ['id', 'title', 'subtitle']), ['entities' => $entities]);
        }, $categories);
    }

    protected function getItems(): array
    {
        $items = $this->itemRepository->findAll();

        return array_map(function (Item $item) {
            return $this->getNecessaryAttributes($item, ['id', 'title', 'price_info']);
        }, $items);
    }
}