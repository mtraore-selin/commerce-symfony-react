<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://dummyjson.com/products?limit=100');
        $data = $response->toArray();



        // Array to store unique category names
        $categoryNames = [];

        foreach ($data['products'] as $productData) {
            if (isset($productData['category']) && !in_array($productData['category'], $categoryNames)) {

                $categoryName = $productData['category'];

                // Create or retrieve Category entity
                $category = new Category();

                if (!in_array($categoryName, $categoryNames)) {
                    $categoryNames[] = $categoryName;

                    // Check if a category with the same name already exists
                    $existingCategory = $manager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

                    if (!$existingCategory) {
                        $category->setName($categoryName);
                        $manager->persist($category);
                    } else {
                        $category = $existingCategory;
                    }
                }
            }

            // Create Product entity
            $product = new Product();
            $product->setTitle($productData['title']);
            $product->setDescription($productData['description']);
            $product->setPrice($productData['price']);
            $product->setDiscountPercentage($productData['discountPercentage']);
            $product->setRating($productData['rating']);
            $product->setStock($productData['stock']);
            $product->setBrand($productData['brand']);
            $product->setThumbnail($productData['thumbnail']);
            $product->setCategory($category);

            // Create and associate ProductImage entities
            foreach ($productData['images'] as $imageUrl) {
                $productImage = new ProductImage();
                $productImage->setUrl($imageUrl);
                $productImage->setProduct($product);
                $manager->persist($productImage);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}
