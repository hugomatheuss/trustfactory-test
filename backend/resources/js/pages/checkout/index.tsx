import EcommerceLayout from '@/layouts/ecommerce-layout';
import { Head, Link, router } from '@inertiajs/react';
import { ShoppingBag, CreditCard, ArrowLeft } from 'lucide-react';

interface Product {
    id: number;
    name: string;
    price: string;
    image: string | null;
}

interface CartItem {
    id: number;
    quantity: number;
    product: Product;
}

interface Cart {
    id: number;
    items: CartItem[];
}

interface Props {
    cart: Cart;
    total: number;
}

export default function CheckoutIndex({ cart, total }: Props) {
    const handleCheckout = () => {
        router.post('/checkout');
    };

    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(price);
    };

    return (
        <EcommerceLayout>
            <Head title="Checkout" />

            <div className="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
                <div className="mb-6">
                    <Link
                        href="/cart"
                        className="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        <ArrowLeft className="size-4" />
                        Back to cart
                    </Link>
                </div>

                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div className="mb-6 flex items-center gap-3 border-b border-gray-200 pb-6 dark:border-gray-800">
                        <div className="flex size-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                            <ShoppingBag className="size-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h1 className="text-xl font-bold text-gray-900 dark:text-white">
                                Checkout
                            </h1>
                            <p className="text-sm text-gray-600 dark:text-gray-400">
                                Review your order
                            </p>
                        </div>
                    </div>

                    {/* Order Items */}
                    <div className="mb-6 space-y-4">
                        <h2 className="font-semibold text-gray-900 dark:text-white">
                            Order Items ({cart.items.length})
                        </h2>
                        <div className="divide-y divide-gray-200 dark:divide-gray-800">
                            {cart.items.map((item) => (
                                <div key={item.id} className="flex gap-4 py-4">
                                    <div className="size-16 shrink-0 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
                                        {item.product.image ? (
                                            <img
                                                src={item.product.image}
                                                alt={item.product.name}
                                                className="size-full object-cover"
                                            />
                                        ) : (
                                            <div className="flex size-full items-center justify-center">
                                                <ShoppingBag className="size-6 text-gray-400" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="flex flex-1 flex-col justify-center">
                                        <p className="font-medium text-gray-900 dark:text-white">
                                            {item.product.name}
                                        </p>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            Qty: {item.quantity} × {formatPrice(parseFloat(item.product.price))}
                                        </p>
                                    </div>
                                    <div className="flex items-center">
                                        <p className="font-semibold text-gray-900 dark:text-white">
                                            {formatPrice(item.quantity * parseFloat(item.product.price))}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Order Summary */}
                    <div className="border-t border-gray-200 pt-6 dark:border-gray-800">
                        <div className="mb-6 flex items-center justify-between">
                            <span className="text-lg font-semibold text-gray-900 dark:text-white">
                                Total
                            </span>
                            <span className="text-2xl font-bold text-gray-900 dark:text-white">
                                {formatPrice(total)}
                            </span>
                        </div>

                        <button
                            onClick={handleCheckout}
                            className="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
                        >
                            <CreditCard className="size-5" />
                            Complete Order
                        </button>
                    </div>
                </div>
            </div>
        </EcommerceLayout>
    );
}

