import React from 'react';

const Toast = ({ show, type = 'success', message, onClose }) => {
    if (!show) return null;
    const bg = type === 'success' ? 'bg-green-600' : 'bg-red-600';
    const text = 'text-white';

    return (
        <div
            className={`fixed z-50 top-6 right-6 px-6 py-4 rounded shadow-md ${bg} ${text} animate-fadeIn`}
            style={{ minWidth: 250 }}
        >
            <div className="flex items-center justify-between">
                <div className="font-semibold">{message}</div>
                <button onClick={onClose} className="ml-4 font-bold text-white">&times;</button>
            </div>
        </div>
    );
};

export default Toast;
