@if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    {{-- Fallback when npm run build has not been run yet --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        },
                        surface: {
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                    },
                },
            },
        };
    </script>
    <style>
        .admin-input {
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            color: #0f172a;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        .admin-input:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 2px rgb(99 102 241 / 0.2);
        }
        .admin-label {
            display: block;
            margin-bottom: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.5rem;
            background: #4f46e5;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover { background: #4338ca; }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            text-decoration: none;
            cursor: pointer;
        }
        .btn-secondary:hover { background: #f8fafc; }
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.5rem;
            background: #dc2626;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .btn-danger:hover { background: #b91c1c; }
        trix-editor {
            min-height: 180px;
            border: 1px solid #cbd5e1;
            border-top: none;
            border-radius: 0 0 0.5rem 0.5rem;
            background: #fff;
            padding: 0.75rem;
            font-size: 0.875rem;
        }
        trix-toolbar {
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem 0.5rem 0 0;
            background: #f8fafc;
        }
        .admin-input.input-error,
        .input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgb(239 68 68 / 0.2);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-confirm]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    const message = form.getAttribute('data-confirm') || 'Are you sure?';
                    if (!window.confirm(message)) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endif
