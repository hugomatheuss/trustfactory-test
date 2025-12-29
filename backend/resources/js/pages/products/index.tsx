import { Head, Link } from '@inertiajs/react';
import EcommerceLayout from '@/layouts/ecommerce-layout';

interface Product {
    id: number;
    name: string;
    description: string | null;
    price: number;
    stock_quantity: number;
    image: string | null;
}

interface PaginatedProducts {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface Props {
    products: PaginatedProducts;
}

export default function Index({ products }: Props) {
    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(price);
    };

    return (
        <EcommerceLayout>
            <Head title="Products" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                            Products
                        </h1>
                        <p className="mt-2 text-gray-600 dark:text-gray-400">
                            Browse our collection of {products.total} products
                        </p>
                    </div>

                    {products.data.length === 0 ? (
                        <div className="rounded-lg bg-white p-12 text-center shadow dark:bg-gray-800">
                            <p className="text-gray-500 dark:text-gray-400">
                                No products available at the moment.
                            </p>
                        </div>
                    ) : (
                        <>
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                {products.data.map((product) => (
                                    <Link
                                        key={product.id}
                                        href={`/products/${product.id}`}
                                        className="group overflow-hidden rounded-lg bg-white shadow transition hover:shadow-lg dark:bg-gray-800"
                                    >
                                        <div className="aspect-square bg-gray-200 dark:bg-gray-700">
                                            {product.image ? (
                                                <img
                                                    src={product.image}
                                                    alt={product.name}
                                                    className="h-full w-full object-cover transition group-hover:scale-105"
                                                />
                                            ) : (
                                                <div className="flex h-full w-full items-center justify-center">
                                                    <svg
                                                        className="h-16 w-16 text-gray-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={1.5}
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                        />
                                                    </svg>
                                                </div>
                                            )}
                                        </div>

                                        <div className="p-4">
                                            <h3 className="truncate font-semibold text-gray-900 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">
                                                {product.name}
                                            </h3>

                                            {product.description && (
                                                <p className="mt-1 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                                                    {product.description}
                                                </p>
                                            )}

                                            <div className="mt-3 flex items-center justify-between">
                                                <span className="text-lg font-bold text-gray-900 dark:text-white">
                                                    {formatPrice(product.price)}
                                                </span>

                                                {product.stock_quantity <= 5 ? (
                                                    <span className="text-xs font-medium text-orange-600 dark:text-orange-400">
                                                        Only {product.stock_quantity} left
                                                    </span>
                                                ) : (
                                                    <span className="text-xs text-gray-500 dark:text-gray-400">
                                                        In stock
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>

                            {products.last_page > 1 && (
                                <div className="mt-8 flex justify-center">
                                    <nav className="flex gap-1">
                                        {products.links.map((link, index) => (
                                            <Link
                                                key={index}
                                                href={link.url || '#'}
                                                className={`rounded px-3 py-2 text-sm ${
                                                    link.active
                                                        ? 'bg-blue-600 text-white'
                                                        : link.url
                                                            ? 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'
                                                            : 'cursor-not-allowed bg-gray-100 text-gray-400 dark:bg-gray-900 dark:text-gray-600'
                                                }`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                            />
                                        ))}
                                    </nav>
                                </div>
                            )}
                        </>
                    )}
                </div>
            </div>
        </EcommerceLayout>
    );
}
