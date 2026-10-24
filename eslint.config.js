import prettier from 'eslint-config-prettier/flat';
import vue from 'eslint-plugin-vue';

import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript';

export default defineConfigWithVueTs(
    vue.configs['flat/essential'],
    vueTsConfigs.recommended,
    {
        ignores: ['vendor', 'node_modules', 'public', 'bootstrap/ssr', 'tailwind.config.js', 'resources/js/components/ui/*', '.claude/hooks/**'],
    },
    {
        rules: {
            'vue/multi-word-component-names': 'off',
            '@typescript-eslint/no-explicit-any': 'off',
            // Admin form components take the page's Inertia form as a prop and bind its fields.
            'vue/no-mutating-props': ['error', { shallowOnly: true }],
        },
    },
    prettier,
);
