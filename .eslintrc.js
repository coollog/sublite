module.exports = {
  'env': {
    'es6': true,
    'browser': true,
    'jquery': true
  },
  'extends': ['eslint:recommended'],
  'installedESLint': true,
  'parserOptions': {
    'ecmaFeatures': {
      'experimentalObjectRestSpread': true,
    },
    'sourceType': 'module'
  },
  'rules': {
    'indent': [
      'error',
      2
    ],
    'linebreak-style': [
      'error',
      'unix'
    ],
    'quotes': [
      'error',
      'single',
      {'avoidEscape': true}
    ],
    'semi': [
      'error',
      'always'
    ]
  }
};
