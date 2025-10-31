<?php

namespace App\Http\Services;

use App\Models\ProductCategory;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductCategoryService
{
    protected $productCategory;
    public function __construct(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    /**
     * Delete the given category and all its children recursively.
     *
     * This method will iterate through all the children of the given category,
     * recursively calling itself to delete each child's children before deleting
     * the child itself. Finally, it will delete the given category.
     *
     * @param \App\Models\ProductCategory $category The category to be deleted along with its children.
     * @return void
     */
    public function deleteCategoryAndChildren($category)
    {
        foreach ($category->children as $child) {
            // Recursively delete the child's children
            $this->deleteCategoryAndChildren($child);
        }
        // Delete the current category
        $category->delete();
    }

    /**
     * Search for product categories by name and include their children.
     *
     * This method searches for product categories whose names contain the given search term.
     * It then retrieves all children of the matching categories and includes them in the result.
     *
     * @param string $search The search term to look for in category names.
     * @return \Illuminate\Support\Collection A collection of matching categories and their children.
     */
    public function searchCategoriesWithChildren($search)
    {
        $categories = $this->productCategory->where('name', 'like', '%' . $search . '%')->get();
        $result = collect();

        foreach ($categories as $category) {
            $result->push($category);
            $result = $result->merge($this->getAllChildren($category));
        }

        return $result;
    }

    /**
     * Retrieve all children categories recursively.
     *
     * This method takes a category and retrieves all its children categories,
     * including the children of its children, and so on. The result is a 
     * collection of all descendant categories.
     *
     * @param \App\Models\ProductCategory $category The category to retrieve children for.
     * @return \Illuminate\Support\Collection A collection of all descendant categories.
     */
    public function getAllChildren($category)
    {
        $children = $category->children;
        $result = collect();

        foreach ($children as $child) {
            $result->push($child);
            $result = $result->merge($this->getAllChildren($child));
        }

        return $result;
    }

    /**
     * Get categories with their respective levels within the hierarchy.
     *
     * This method processes a list of categories and calculates the level of each category
     * within the hierarchy. It ensures that each category is processed only once.
     *
     * @param array $categories An array of category objects to be processed.
     * @return array An array of category objects with an additional 'level' property.
     */
    public function getCategoriesWithLevels($categories)
    {
        $result = [];
        $processedIds = [];

        foreach ($categories as $category) {
            // Ensure we only process each category once
            if (!in_array($category->id, $processedIds)) {
                // Calculate the level of the category within the hierarchy
                $level = $this->calculateCategoryLevel($category);
                $category->level = $level;
                $result[] = $category;
                $processedIds[] = $category->id;
            }
        }
        return $result;
    }

    /**
     * Calculate the level of a given category in the category hierarchy.
     *
     * This function traverses up the category hierarchy by following the parent
     * categories and increments the level count until it reaches the top-level category.
     *
     * @param object $category The category object for which the level needs to be calculated.
     * @return int The level of the category in the hierarchy.
     */
    private function calculateCategoryLevel($category)
    {
        $level = 0;
        while ($category->parent) {
            $level++;
            $category = $category->parent;
        }
        return $level;
    }


    /**
     * Retrieve all categories with their respective levels.
     *
     * This method processes a collection of categories and returns a collection
     * where each category is accompanied by its hierarchical level. It ensures
     * that each category is processed only once.
     *
     * @param \Illuminate\Support\Collection $categories A collection of category objects to process.
     * @return \Illuminate\Support\Collection A collection of categories with their respective levels.
     */
    public function getAllCategoriesWithLevels($categories)
    {
        $result = collect(); // Use a collection instead of an array
        $processedIds = [];

        foreach ($categories as $category) {
            if (!in_array($category->id, $processedIds)) {
                $result = $result->merge($this->getCategoriesWithChildrenAndLevel($category, 0, $processedIds));
            }
        }

        return $result;
    }

    /**
     * Recursively retrieves categories with their children and assigns a level to each category.
     *
     * @param \App\Models\ProductCategory $category The category to start from.
     * @param int $level The current level of the category.
     * @param array $processedIds An array to keep track of processed category IDs to avoid duplication.
     * @return \Illuminate\Support\Collection A collection of categories with their children and levels.
     */
    public function getCategoriesWithChildrenAndLevel($category, $level, &$processedIds)
    {
        $category->level = $level;
        $result = collect([$category]); // Use a collection instead of an array
        $processedIds[] = $category->id;

        foreach ($category->children as $child) {
            if (!in_array($child->id, $processedIds)) {
                $result = $result->merge($this->getCategoriesWithChildrenAndLevel($child, $level + 1, $processedIds));
            }
        }

        return $result;
    }

    /**
     * Paginate the given items.
     *
     * @param array $items The items to paginate.
     * @param int $perPage The number of items per page.
     * @param int $currentPage The current page number.
     * @param array $queryParams Additional query parameters for pagination.
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated items.
     */
    public function paginate($items, $perPage, $currentPage, $queryParams)
    {
        $options = array_merge([
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
            'query' => $queryParams,
        ]);

        $items = collect($items);
        $total = $items->count();
        $itemsForCurrentPage = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($itemsForCurrentPage, $total, $perPage, $currentPage, $options);
    }
}
