import { usePage } from '@inertiajs/react';
import { CheckCircle, XCircle, X } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';

interface FlashMessage {
    success?: string;
    error?: string;
}

export function FlashMessages() {
    const { flash } = usePage<{ flash: FlashMessage }>().props;
    const [visible, setVisible] = useState(false);

    const message = useMemo(() => {
        if (flash?.success) {
            return { type: 'success' as const, text: flash.success };
        }
        if (flash?.error) {
            return { type: 'error' as const, text: flash.error };
        }
        return null;
    }, [flash?.success, flash?.error]);

    useEffect(() => {
        if (message) {
            setVisible(true);
            const timer = setTimeout(() => {
                setVisible(false);
            }, 4000);
            return () => clearTimeout(timer);
        }
    }, [message]);

    if (!visible || !message) {
        return null;
    }

    return (
        <div className="fixed bottom-4 right-4 z-50 animate-in slide-in-from-bottom-5 fade-in duration-300">
            <div
                className={`flex items-center gap-3 rounded-lg px-4 py-3 shadow-lg ${
                    message.type === 'success'
                        ? 'bg-green-600 text-white'
                        : 'bg-red-600 text-white'
                }`}
            >
                {message.type === 'success' ? (
                    <CheckCircle className="size-5 shrink-0" />
                ) : (
                    <XCircle className="size-5 shrink-0" />
                )}
                <span className="text-sm font-medium">{message.text}</span>
                <button
                    onClick={() => setVisible(false)}
                    className="ml-2 rounded-full p-1 transition-colors hover:bg-white/20"
                >
                    <X className="size-4" />
                    <span className="sr-only">Close</span>
                </button>
            </div>
        </div>
    );
}

