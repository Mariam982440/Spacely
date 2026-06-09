<style>
    :root {
        --spacely-bg: #e5dfd0;
        --spacely-surface: #f4efe4;
        --spacely-surface-2: #ddd4c2;
        --spacely-border: #5f543f;
        --spacely-border-strong: #342d21;
        --spacely-text: #17140f;
        --spacely-muted: #594f3d;
        --spacely-green: #385a24;
        --spacely-green-strong: #253d18;
        --spacely-green-soft: #d5dfc7;
        --spacely-green-soft-text: #253d18;
        --spacely-red-soft: #ead0ca;
        --spacely-red: #8f2f25;
        --spacely-blue-soft: #ccdae4;
        --spacely-blue: #244b68;
        --spacely-yellow-soft: #e3d3a9;
        --spacely-yellow: #735d1f;
        color-scheme: light;
    }

    :root[data-theme="dark"] {
        --spacely-bg: #14130f;
        --spacely-surface: #1d1a14;
        --spacely-surface-2: #252117;
        --spacely-border: #3b3426;
        --spacely-border-strong: #5a4d35;
        --spacely-text: #eee8d8;
        --spacely-muted: #b9ad91;
        --spacely-green: #324f1f;
        --spacely-green-strong: #203915;
        --spacely-green-soft: #26381d;
        --spacely-green-soft-text: #dcefc7;
        --spacely-red-soft: #3a1717;
        --spacely-red: #ef8f83;
        --spacely-blue-soft: #172b3d;
        --spacely-blue: #9fc8ee;
        --spacely-yellow-soft: #3b3015;
        --spacely-yellow: #e5c06d;
        color-scheme: dark;
    }

    body {
        background: var(--spacely-bg) !important;
        color: var(--spacely-text) !important;
    }

    nav {
        background-color: color-mix(in srgb, var(--spacely-surface) 92%, transparent) !important;
        border-bottom: 1px solid var(--spacely-border) !important;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08) !important;
    }

    nav > div {
        height: auto !important;
        min-height: 4rem;
        gap: 1rem;
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
        flex-wrap: wrap;
    }

    nav > div > div {
        flex-wrap: wrap;
    }

    nav a {
        border-radius: 3px !important;
    }

    nav a:not(.font-serif) {
        border: 1px solid transparent !important;
    }

    nav a:not(.font-serif):hover {
        background-color: var(--spacely-surface-2) !important;
        border-color: var(--spacely-border) !important;
    }

    footer,
    main,
    [class*="bg-stone-50"],
    [class*="bg-beige"] {
        background-color: var(--spacely-bg) !important;
        color: var(--spacely-text) !important;
    }

    [class*="bg-white"],
    [class*="bg-stone-100"],
    [class*="bg-stone-50"],
    [class*="bg-beige"],
    [class*="bg-gray-50"],
    input,
    select,
    textarea,
    table,
    tbody,
    thead {
        background-color: var(--spacely-surface) !important;
        color: var(--spacely-text) !important;
    }

    [class*="hover:bg-stone-50"]:hover,
    [class*="hover:bg-stone-100"]:hover,
    [class*="hover:bg-white"]:hover,
    [class*="hover:bg-beige"]:hover {
        background-color: var(--spacely-surface-2) !important;
    }

    [class*="bg-green-50"],
    [class*="bg-green-100"],
    [class*="bg-sage"],
    [class*="peer-checked:bg-sage"] {
        background-color: var(--spacely-green-soft) !important;
        color: var(--spacely-green-soft-text) !important;
    }

    [class*="bg-green-600"],
    [class*="bg-green-700"],
    [class*="bg-green-800"],
    button[type="submit"] {
        background-color: var(--spacely-green) !important;
        color: #f3f8ec !important;
    }

    [class*="hover:bg-green-800"]:hover,
    [class*="hover:bg-sage-dark"]:hover,
    button[type="submit"]:hover {
        background-color: var(--spacely-green-strong) !important;
    }

    [class*="bg-red-50"],
    [class*="bg-red-100"] {
        background-color: var(--spacely-red-soft) !important;
        color: var(--spacely-red) !important;
    }

    [class*="bg-blue-100"] {
        background-color: var(--spacely-blue-soft) !important;
        color: var(--spacely-blue) !important;
    }

    [class*="bg-yellow-100"] {
        background-color: var(--spacely-yellow-soft) !important;
        color: var(--spacely-yellow) !important;
    }

    [class*="text-stone-"],
    [class*="text-gray-"],
    [class*="text-charcoal"] {
        color: var(--spacely-muted) !important;
    }

    h1,
    h2,
    h3,
    .font-serif,
    [class*="text-stone-900"],
    [class*="text-stone-800"] {
        color: var(--spacely-text) !important;
    }

    [class*="text-green-"],
    [class*="text-sage"] {
        color: var(--spacely-green) !important;
    }

    [class*="text-red-"] {
        color: var(--spacely-red) !important;
    }

    [class*="border"],
    input,
    select,
    textarea,
    label,
    table,
    th,
    td {
        border-color: var(--spacely-border) !important;
    }

    [class~="border"],
    input,
    select,
    textarea {
        border-width: 1px !important;
    }

    [class*="border-b"] {
        border-bottom-width: 1px !important;
    }

    [class*="border-t"] {
        border-top-width: 1px !important;
    }

    [class*="border-l"] {
        border-left-width: 2px !important;
    }

    [class*="border-r"] {
        border-right-width: 1px !important;
    }

    [class*="focus:border-green"],
    [class*="focus:border-sage"],
    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--spacely-border-strong) !important;
        box-shadow: 0 0 0 3px rgba(90, 77, 53, 0.3) !important;
    }

    [class*="rounded"],
    [class*="rounded-["] {
        border-radius: 4px !important;
    }

    [class*="shadow"] {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12) !important;
    }

    img,
    video {
        border-color: var(--spacely-border) !important;
    }

    ::placeholder {
        color: #7f735c !important;
    }

    .spacely-theme-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        border: 1px solid var(--spacely-border) !important;
        border-radius: 4px !important;
        background: var(--spacely-surface) !important;
        color: var(--spacely-text) !important;
        cursor: pointer;
        transition: background-color 150ms ease, border-color 150ms ease;
    }

    .spacely-theme-toggle:hover {
        background: var(--spacely-surface-2) !important;
        border-color: var(--spacely-border-strong) !important;
    }

    .spacely-theme-toggle svg {
        width: 1rem;
        height: 1rem;
    }

    :root[data-theme="dark"] .spacely-sun-icon,
    :root:not([data-theme="dark"]) .spacely-moon-icon {
        display: none;
    }
</style>
<script>
    (function () {
        const root = document.documentElement;
        const savedTheme = localStorage.getItem('spacely-theme');

        if (savedTheme === 'dark') {
            root.setAttribute('data-theme', 'dark');
        } else {
            root.setAttribute('data-theme', 'light');
        }

        window.toggleSpacelyTheme = function () {
            const nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', nextTheme);
            localStorage.setItem('spacely-theme', nextTheme);
        };
    })();
</script>
