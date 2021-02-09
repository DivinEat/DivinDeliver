<?php


namespace App\Service;


use App\Entity\Category;
use App\Entity\Item;
use App\Entity\Menu;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\MenuRepository;
use App\SDK\UberEats\MenuSDK;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Serializer;

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

    public function __construct(MenuSDK $menuSDK, MenuRepository $menuRepository, CategoryRepository $categoryRepository, ItemRepository $itemRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->categoryRepository = $categoryRepository;
        $this->itemRepository = $itemRepository;
        $this->menuSDK = $menuSDK;
    }

    public function upload()
    {
        $this->menuSDK->uploadMenu('c41cb075-a830-40ca-bf66-912fd69d8df7', [
            'menus' => $this->getMenus(),
            'categories' => $this->getCategories(),
            'items' => $this->getItems(),
            'menu_type' => 'MENU_TYPE_FULFILLMENT_DELIVERY'
        ]);
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