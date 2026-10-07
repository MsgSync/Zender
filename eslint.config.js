const js = require("@eslint/js");
const globals = require("globals");

module.exports = [
    {
        ignores: [
            "node_modules/**",
            "vendor/**",
            "templates/_assets/**",
            "templates/_apidoc/**",
            "templates/*/assets/**",
            "docs/**",
            "system/storage/**",
            "uploads/**"
        ]
    },
    js.configs.recommended,
    {
        files: ["**/*.js"],
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: "commonjs",
            globals: {
                ...globals.node
            }
        },
        rules: {
            "no-unused-vars": ["error", { argsIgnorePattern: "^_" }],
            "no-console": "off"
        }
    }
];
