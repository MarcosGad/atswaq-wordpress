module.exports = {
	plugins: [ 'es5' ],
	extends: [ 'eslint:recommended', 'plugin:es5/no-es2015' ],
	env: {
		browser: true,
		jquery: true,
	},
	globals: {
		_: false,
		Backbone: false,
		// jQuery: false,
		wp: false,
	},
	parserOptions: {
		ecmaVersion: 5,
	},
	rules: {
		// TODO: maybe fix the above warnings
		eqeqeq: 'warn',
		curly: 'warn',
		'no-console': 'warn',
		'no-empty': 'warn',
		'no-unused-vars': 'warn',
		'es5/no-es6-methods': 0, // TODO: maybe disable it on line/file basis
		'no-useless-escape': 0,
		'no-control-regex': 0,
		'no-unused-expressions': 'warn',

		'es5/no-block-scoping': [ 'error' ],
		'no-caller': 'error',
		'no-cond-assign': [ 'error', 'except-parens' ],
		'no-eq-null': 'error',
		'no-irregular-whitespace': 'error',
		'no-trailing-spaces': 'error',
		'no-undef': 'error',
		// 'one-var': [ 'error', 'always' ],
		quotes: [ 'warn', 'single', { avoidEscape: true } ],
		'wrap-iife': [ 'error', 'any' ],
	},
};
