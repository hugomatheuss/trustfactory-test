import { Head, Link, router } from '@inertiajs/react';
import EcommerceLayout from '@/layouts/ecommerce-layout';

interface Product {
    id: number;
    name: string;
    price: number;
    image: string | null;
    stock_quantity: number;
}

interface CartItem {
    id: number;
    cart_id: number;
    product_id: number;
    quantity: number;
    product: Product;
}

interface Cart {
    id: number;
    user_id: number;
    items: CartItem[];
}

interface Props {
    cart: Cart;
    total: number;
}

export default function Index({ cart, total }: Props) {
    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(price);
    };

    const updateQuantity = (item: CartItem, newQuantity: number) => {
        if (newQuantity < 1 || newQuantity > item.product.stock_quantity) {
            return;
        }

        router.patch(`/cart/items/${item.id}`, {
            quantity: newQuantity,
        }, {
            preserveScroll: true,
        });
    };

    const removeItem = (item: CartItem) => {
        router.delete(`/cart/items/${item.id}`, {
            preserveScroll: true,
        });
    };

    return (
        <EcommerceLayout>
            <Head title="Shopping Cart" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    <h1 className="mb-8 text-3xl font-bold text-gray-900 dark:text-white">
                        Shopping Cart
                    </h1>

                    {cart.items.length === 0 ? (
                        <div className="rounded-lg bg-white p-12 text-center shadow dark:bg-gray-800">
                            <svg
                                className="mx-auto h-16 w-16 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={1.5}
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <h2 className="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Your cart is empty
                            </h2>
                            <p className="mt-2 text-gray-500 dark:text-gray-400">
                                Start shopping to add items to your cart.
                            </p>
                            <Link
                                href="/products"
                                className="mt-6 inline-block rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
                            >
                                Browse Products
                            </Link>
                        </div>
                    ) : (
                        <div className="grid gap-8 lg:grid-cols-3">
                            <div className="lg:col-span-2">
                                <div className="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                                    <ul className="divide-y divide-gray-200 dark:divide-gray-700">
                                        {cart.items.map((item) => (
                                            <li key={item.id} className="p-4 sm:p-6">
                                                <div className="flex gap-4">
                                                    <div className="h-24 w-24 shrink-0 overflow-hidden rounded-lg bg-gray-200 dark:bg-gray-700">
                                                        {item.product.image ? (
                                                            <img
                                                                src={item.product.image}
                                                                alt={item.product.name}
                                                                className="h-full w-full object-cover"
                                                            />
                                                        ) : (
                                                            <div className="flex h-full w-full items-center justify-center">
                                                                <svg
                                                                    className="h-8 w-8 text-gray-400"
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

                                                    <div className="flex flex-1 flex-col">
                                                        <div className="flex justify-between">
                                                            <Link
                                                                href={`/products/${item.product.id}`}
                                                                className="font-semibold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                                                            >
                                                                {item.product.name}
                                                            </Link>
                                                            <span className="font-semibold text-gray-900 dark:text-white">
                                                                {formatPrice(item.product.price * item.quantity)}
                                                            </span>
                                                        </div>

                                                        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                            {formatPrice(item.product.price)} each
                                                        </p>

                                                        <div className="mt-auto flex items-center justify-between pt-4">
                                                            <div className="flex items-center rounded-lg border border-gray-300 dark:border-gray-600">
                                                                <button
                                                                    type="button"
                                                                    onClick={() => updateQuantity(item, item.quantity - 1)}
                                                                    disabled={item.quantity <= 1}
                                                                    className="px-3 py-1 text-gray-600 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-700"
                                                                >
                                                                    −
                                                                </button>
                                                                <span className="w-10 text-center text-sm text-gray-900 dark:text-white">
                                                                    {item.quantity}
                                                                </span>
                                                                <button
                                                                    type="button"
                                                                    onClick={() => updateQuantity(item, item.quantity + 1)}
                                                                    disabled={item.quantity >= item.product.stock_quantity}
                                                                    className="px-3 py-1 text-gray-600 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:text-gray-400 dark:hover:bg-gray-700"
                                                                >
                                                                    +
                                                                </button>
                                                            </div>

                                                            <button
                                                                type="button"
                                                                onClick={() => removeItem(item)}
                                                                className="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                            >
                                                                Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>

                            <div className="lg:col-span-1">
                                <div className="sticky top-4 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white">
                                        Order Summary
                                    </h2>

                                    <div className="mt-4 space-y-3">
                                        <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Subtotal ({cart.items.length} items)</span>
                                            <span>{formatPrice(total)}</span>
                                        </div>
                                        <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Shipping</span>
                                            <span>Free</span>
                                        </div>
                                    </div>

                                    <div className="mt-4 border-t border-gray-200 pt-4 dark:border-gray-700">
                                        <div className="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                            <span>Total</span>
                                            <span>{formatPrice(total)}</span>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        className="mt-6 w-full rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        Proceed to Checkout
                                    </button>

                                    <Link
                                        href="/products"
                                        className="mt-3 block text-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                    >
                                        Continue Shopping
                                    </Link>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </EcommerceLayout>
    );
}
