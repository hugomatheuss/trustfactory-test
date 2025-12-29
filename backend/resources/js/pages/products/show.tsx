import { Head, Link, useForm } from '@inertiajs/react';
import EcommerceLayout from '@/layouts/ecommerce-layout';
import { FormEvent } from 'react';

interface Product {
    id: number;
    name: string;
    description: string | null;
    price: number;
    stock_quantity: number;
    image: string | null;
}

interface Props {
    product: Product;
    auth: {
        user: { id: number; name: string } | null;
    };
}

export default function Show({ product, auth }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        product_id: product.id,
        quantity: 1,
    });

    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(price);
    };

    const handleAddToCart = (e: FormEvent) => {
        e.preventDefault();
        post('/cart/add');
    };

    const incrementQuantity = () => {
        if (data.quantity < product.stock_quantity) {
            setData('quantity', data.quantity + 1);
        }
    };

    const decrementQuantity = () => {
        if (data.quantity > 1) {
            setData('quantity', data.quantity - 1);
        }
    };

    return (
        <EcommerceLayout>
            <Head title={product.name} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Link
                        href="/products"
                        className="mb-6 inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Products
                    </Link>

                    <div className="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                        <div className="grid gap-8 md:grid-cols-2">
                            <div className="aspect-square bg-gray-200 dark:bg-gray-700">
                                {product.image ? (
                                    <img
                                        src={product.image}
                                        alt={product.name}
                                        className="h-full w-full object-cover"
                                    />
                                ) : (
                                    <div className="flex h-full w-full items-center justify-center">
                                        <svg
                                            className="h-24 w-24 text-gray-400"
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

                            <div className="p-6 md:p-8">
                                <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                                    {product.name}
                                </h1>

                                <p className="mt-4 text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {formatPrice(product.price)}
                                </p>

                                {product.description && (
                                    <p className="mt-4 text-gray-600 dark:text-gray-400">
                                        {product.description}
                                    </p>
                                )}

                                <div className="mt-6">
                                    {product.stock_quantity > 0 ? (
                                        <span className="inline-flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                                            <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fillRule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clipRule="evenodd"
                                                />
                                            </svg>
                                            {product.stock_quantity <= 5
                                                ? `Only ${product.stock_quantity} left in stock`
                                                : 'In stock'}
                                        </span>
                                    ) : (
                                        <span className="inline-flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                                            <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fillRule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clipRule="evenodd"
                                                />
                                            </svg>
                                            Out of stock
                                        </span>
                                    )}
                                </div>

                                {auth.user && product.stock_quantity > 0 && (
                                    <form onSubmit={handleAddToCart} className="mt-8">
                                        <div className="flex items-center gap-4">
                                            <label className="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Quantity:
                                            </label>
                                            <div className="flex items-center rounded-lg border border-gray-300 dark:border-gray-600">
                                                <button
                                                    type="button"
                                                    onClick={decrementQuantity}
                                                    disabled={data.quantity <= 1}
                                                    className="px-3 py-2 text-gray-600 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-700"
                                                >
                                                    −
                                                </button>
                                                <span className="w-12 text-center text-gray-900 dark:text-white">
                                                    {data.quantity}
                                                </span>
                                                <button
                                                    type="button"
                                                    onClick={incrementQuantity}
                                                    disabled={data.quantity >= product.stock_quantity}
                                                    className="px-3 py-2 text-gray-600 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-700"
                                                >
                                                    +
                                                </button>
                                            </div>
                                        </div>

                                        {errors.quantity && (
                                            <p className="mt-2 text-sm text-red-600 dark:text-red-400">
                                                {errors.quantity}
                                            </p>
                                        )}

                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className="mt-6 w-full rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            {processing ? 'Adding...' : 'Add to Cart'}
                                        </button>
                                    </form>
                                )}

                                {!auth.user && product.stock_quantity > 0 && (
                                    <div className="mt-8">
                                        <Link
                                            href="/login"
                                            className="block w-full rounded-lg bg-blue-600 px-6 py-3 text-center font-semibold text-white transition hover:bg-blue-700"
                                        >
                                            Login to Add to Cart
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </EcommerceLayout>
    );
}
