import React from 'react';

export default function Card({ title, subtitle, children }) {
  return (
    <div className="rounded-lg border border-zinc-200/60 dark:border-zinc-800/60 bg-white/60 dark:bg-zinc-900/60 shadow-sm p-6">
      <h3 className="text-lg font-semibold">{title}</h3>
      {subtitle && <p className="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{subtitle}</p>}
      {children && <div className="mt-4 text-sm">{children}</div>}
    </div>
  );
}

