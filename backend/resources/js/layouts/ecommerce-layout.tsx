import { EcommerceNavbar } from '@/components/ecommerce-navbar';
import { FlashMessages } from '@/components/flash-messages';
import type { PropsWithChildren } from 'react';

export default function EcommerceLayout({ children }: PropsWithChildren) {
    return (
        <div className="flex min-h-screen flex-col bg-gray-50 dark:bg-gray-900">
            <EcommerceNavbar />
            <main className="flex-1">{children}</main>
            <footer className="border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
                <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div className="flex flex-col items-center justify-between gap-4 sm:flex-row">
                        <p className="text-sm text-gray-500 dark:text-gray-400">
                            © {new Date().getFullYear()} ShopStore. All rights reserved.
                        </p>
                        <div className="flex gap-6">
                            <a
                                href="#"
                                className="text-sm text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                            >
                                Privacy Policy
                            </a>
                            <a
                                href="#"
                                className="text-sm text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                            >
                                Terms of Service
                            </a>
                            <a
                                href="#"
                                className="text-sm text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                            >
                                Contact
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
            <FlashMessages />
        </div>
    );
}

