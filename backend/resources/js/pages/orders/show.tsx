import EcommerceLayout from '@/layouts/ecommerce-layout';
import { Head, Link } from '@inertiajs/react';
import { CheckCircle, ShoppingBag, ArrowRight } from 'lucide-react';

interface Product {
    id: number;
    name: string;
    price: string;
    image: string | null;
}

interface OrderItem {
    id: number;
    quantity: number;
    price: string;
    product: Product;
}

interface Order {
    id: number;
    total: string;
    status: string;
    created_at: string;
    items: OrderItem[];
}

interface Props {
    order: Order;
}

export default function OrderShow({ order }: Props) {
    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(price);
    };

    return (
        <EcommerceLayout>
            <Head title={`Order #${order.id}`} />

            <div className="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    {/* Success Header */}
                    <div className="mb-8 text-center">
                        <div className="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                            <CheckCircle className="size-8 text-green-600 dark:text-green-400" />
                        </div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                            Order Confirmed!
                        </h1>
                        <p className="mt-2 text-gray-600 dark:text-gray-400">
                            Thank you for your purchase. Your order #{order.id} has been placed.
                        </p>
                    </div>

                    {/* Order Details */}
                    <div className="mb-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                        <div className="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p className="text-gray-600 dark:text-gray-400">Order Number</p>
                                <p className="font-semibold text-gray-900 dark:text-white">
                                    #{order.id}
                                </p>
                            </div>
                            <div>
                                <p className="text-gray-600 dark:text-gray-400">Status</p>
                                <span className="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                    {order.status}
                                </span>
                            </div>
                            <div>
                                <p className="text-gray-600 dark:text-gray-400">Date</p>
                                <p className="font-semibold text-gray-900 dark:text-white">
                                    {new Date(order.created_at).toLocaleDateString()}
                                </p>
                            </div>
                            <div>
                                <p className="text-gray-600 dark:text-gray-400">Total</p>
                                <p className="font-semibold text-gray-900 dark:text-white">
                                    {formatPrice(parseFloat(order.total))}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Order Items */}
                    <div className="mb-6">
                        <h2 className="mb-4 font-semibold text-gray-900 dark:text-white">
                            Items Ordered
                        </h2>
                        <div className="divide-y divide-gray-200 dark:divide-gray-800">
                            {order.items.map((item) => (
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
                                            Qty: {item.quantity} × {formatPrice(parseFloat(item.price))}
                                        </p>
                                    </div>
                                    <div className="flex items-center">
                                        <p className="font-semibold text-gray-900 dark:text-white">
                                            {formatPrice(item.quantity * parseFloat(item.price))}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Actions */}
                    <div className="border-t border-gray-200 pt-6 dark:border-gray-800">
                        <Link
                            href="/products"
                            className="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
                        >
                            Continue Shopping
                            <ArrowRight className="size-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </EcommerceLayout>
    );
}

