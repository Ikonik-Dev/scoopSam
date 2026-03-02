<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class ProductService
{
        public function __construct(
            private ProductRepository $productRepository,
            private CategoryRepository $categoryRepository
        ){}

        /**
         * @return \App\Entity\Product[]
         */
        public function getAvailableProducts(): array
        {
            return $this->productRepository->findAvailable();
        }

        /**
         * @return \App\Entity\Product[]
         */
        public function getProductByCategory(int $categoryId): array
        {
            return $this->productRepository->findAvailableByCategory($categoryId);
        }

        /**
         * @return \App\Entity\Category[]
         */
        public function getAllCategories(): array
        {
            return $this->categoryRepository->findAll();
        }

        public function getProductById(int $productId): ?Product
        {
            return $this->productRepository->find($productId);
        }
}