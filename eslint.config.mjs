import js from '@eslint/js';
import tseslint from 'typescript-eslint';
import vitest from '@vitest/eslint-plugin';

export default tseslint.config(
    {
        ignores: ['public/build/**', 'node_modules/**', 'var/**'],
    },
    js.configs.recommended,
    ...tseslint.configs.strict,
    {
        languageOptions: {
            parserOptions: {
                project: './tsconfig.json',
            },
        },
    },
    {
        files: ['assets/**/*.spec.ts'],
        plugins: { vitest },
        rules: {
            ...vitest.configs.recommended.rules,
        },
    },
);
