<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create categories first
        $categories = [];
        foreach (['Peripherals', 'Displays', 'Accessories', 'Audio'] as $name) {
            $cat = new Category();
            $cat->setName($name);
            $manager->persist($cat);
            $categories[$name] = $cat;
        }

        // Create products with category relation
        $products = [
            ['name' => 'Mechanical Keyboard',        'description' => 'TKL RGB mechanical keyboard',          'price' => 99.99,  'category' => 'Peripherals'],
            ['name' => 'Wireless Mouse',              'description' => 'Ergonomic wireless mouse',              'price' => 49.99,  'category' => 'Peripherals'],
            ['name' => 'Gaming Mouse',                'description' => 'High DPI gaming mouse',                 'price' => 69.99,  'category' => 'Peripherals'],
            ['name' => '4K Monitor',                  'description' => '27-inch 4K IPS display',                'price' => 399.99, 'category' => 'Displays'],
            ['name' => '144Hz Gaming Monitor',        'description' => '24-inch 1080p 144Hz display',           'price' => 249.99, 'category' => 'Displays'],
            ['name' => 'USB-C Hub',                   'description' => '7-in-1 USB-C multiport adapter',        'price' => 39.99,  'category' => 'Accessories'],
            ['name' => 'Laptop Stand',                'description' => 'Adjustable aluminium laptop stand',     'price' => 29.99,  'category' => 'Accessories'],
            ['name' => 'Noise-Cancelling Headphones', 'description' => 'Over-ear ANC headphones',               'price' => 199.99, 'category' => 'Audio'],
            ['name' => 'Desktop Speakers',            'description' => 'Stereo bookshelf speakers',             'price' => 129.99, 'category' => 'Audio'],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $product->setCategory($categories[$data['category']]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
