<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class CategoryService
{
    /**
     * Get all categories
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return Category::query()->orderBy('name')->get();
    }

    /**
     * Get category by ID
     * 
     * @param int $categoryId
     * @return Category
     */
    public function getCategoryById($categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    /**
     * Create a new category
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return Category
     */
    public function createCategory(Request $request)
    {
        $category = new Category();
        return $this->saveCategoryData($category, $request);
    }

    /**
     * Update existing category
     * 
     * @param int $categoryId
     * @param  \Illuminate\Http\Request  $request
     * @return Category
     */
    public function updateCategory($categoryId, Request $request)
    {
        $category = $this->getCategoryById($categoryId);
        return $this->saveCategoryData($category, $request);
    }

    /**
     * Save category data with validation
     * 
     * @param Category $category
     * @param  \Illuminate\Http\Request  $request
     * @return Category
     */
    private function saveCategoryData(Category $category, Request $request)
    {
        $input = $request->all();

        if ($category->validate($input)) {
            $category->fill($input);
            $category->save();
            return $category;
        }

        throw new \Exception($category->errors()->first());
    }

    /**
     * Delete a single category
     * 
     * @param int $categoryId
     * @return bool
     */
    public function deleteCategory($categoryId)
    {
        $category = $this->getCategoryById($categoryId);

        if (Service::where('category_id', $category->id)->exists()) {
            throw new \Exception('Não é possível eliminar uma categoria que tenha serviços associados.');
        }

        if (!$category->delete()) {
            throw new \Exception('Ocorreu um erro ao tentar remover a categoria.');
        }

        return true;
    }

    /**
     * Delete multiple categories
     * 
     * @param array $ids
     * @return bool
     */
    public function deleteMultipleCategories(array $ids)
    {
        $categoriesWithServices = Service::whereIn('category_id', $ids)
            ->distinct()
            ->pluck('category_id')
            ->toArray();

        if (!empty($categoriesWithServices)) {
            throw new \Exception('Não é possível eliminar categorias que tenham serviços associados.');
        }

        $result = Category::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new \Exception('Não foi possível remover os registos selecionados');
        }

        return true;
    }

    /**
     * Get datatable data for categories
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableData()
    {
        return Category::query();
    }

    /**
     * Get data for index view
     * 
     * @return array
     */
    public function getIndexData()
    {
        return [];
    }

    /**
     * Get data for create form
     * 
     * @return array
     */
    public function getCreateData()
    {
        return [
            'action' => 'Novo Categoria',
            'category' => new Category(),
            'formOptions' => [
                'route' => ['admin.categorys.store']
            ]
        ];
    }

    /**
     * Get data for edit form
     * 
     * @param int $categoryId
     * @return array
     */
    public function getEditData($categoryId)
    {
        $category = $this->getCategoryById($categoryId);

        return [
            'action' => 'Editar Categoria',
            'category' => $category,
            'formOptions' => [
                'route' => ['admin.categorys.update', $category->id],
                'method' => 'PUT'
            ]
        ];
    }
}
