<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\FormCategoryRequest;

class CategoryController extends Controller
{
    public function __construct(
        private readonly \App\Repositories\Interface\CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function getList(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'q' => $request->query('q', ''),
        ];

        $categories = $this->categoryRepository->getCategories($filters, $perPage);
        $categories->appends($request->query());

        return $this->successResponse($categories);
    }

    public function getDetail(int $id)
    {
        $category = $this->categoryRepository->getDetail($id);

        if (!$category) {
            return $this->errorResponse('Category not found.');
        }

        return $this->successResponse($category);
    }

    public function create(FormCategoryRequest $request)
    {
        $data = $request->validated();

        $category = $this->categoryRepository->create($data);

        return $this->successResponse($category, 'Category created successfully.', 201);
    }

    public function update(int $id, FormCategoryRequest $request)
    {
        $data = $request->validated();

        $category = $this->categoryRepository->update($id, $data);

        if (!$category) {
            return $this->errorResponse('Category not found.');
        }

        return $this->successResponse($category, 'Category updated successfully.');
    }

    public function delete(int $id)
    {
        $category = $this->categoryRepository->getDetail($id);

        if (!$category) {
            return $this->errorResponse('Category not found.');
        }

        if ($category->books->isNotEmpty()) {
            return $this->errorResponse('Cannot delete category with associated books.');
        }

        $deleted = $this->categoryRepository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Failed to delete category.');
        }

        return $this->successResponse('Category deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('Invalid category IDs.');
        }

        $deletedCount = $this->categoryRepository->bulkDelete($ids);

        return $this->successResponse("Deleted {$deletedCount} categories successfully.");
    }
}
