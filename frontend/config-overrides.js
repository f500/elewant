'use strict';

const ExtractTextPlugin = require('extract-text-webpack-plugin');
const path = require('path');
const { loaderNameMatches } = require('react-app-rewired');

module.exports = (config, env) => {
  const envIsProd = env === 'production';

  config = rewireSass(config, envIsProd);

  return config;
};

const rewireSass = (config, envIsProd) => {
  /*
   * Build style-loader rules
   */

  // Find current index & rules
  let styleIndexAndRules = findIndexAndRules(config.module.rules, rule => {
    return String(rule.test) === String(/\.css$/);
  });

  // Find the exact loader
  let styleLoader = !envIsProd
    ? styleIndexAndRules.rules[styleIndexAndRules.index].use[0]
    : findRule(styleIndexAndRules.rules[styleIndexAndRules.index], rule => loaderNameMatches(rule, 'style-loader'))
        .loader;

  // Build new rules
  let styleRules = {
    loader: styleLoader,
    options: {
      hmr: !envIsProd
    }
  };

  /*
   * Build css-loader rules
   */

  // Find current rules
  let cssRules = findRule(config.module.rules, rule => loaderNameMatches(rule, 'css-loader'));

  // Append options
  Object.assign(cssRules.options, {
    importLoaders: 2,
    minimize: envIsProd,
    sourceMap: !envIsProd
  });

  /*
   * Build css-loader rules
   */

  // Find current rules
  let postcssRules = findRule(config.module.rules, rule => loaderNameMatches(rule, 'postcss-loader'));

  // Append options
  Object.assign(postcssRules.options, {
    sourceMap: !envIsProd
  });

  /*
   * Build css-loader rules
   */

  let sassRules = {
    loader: path.resolve(__dirname, 'node_modules/sass-loader/lib/loader.js'),
    options: {
      sourceMap: !envIsProd
    }
  };

  /*
   * Add chain of loader-rules
   */

  // Build chain for development
  let sassChainDev = {
    test: /(\.scss|\.sass)$/,
    use: [styleRules, cssRules, postcssRules, sassRules]
  };

  // Build chain for production
  let sassChainProd = {
    test: /(\.scss|\.sass)$/,
    use: ExtractTextPlugin.extract({
      fallback: styleRules,
      use: [cssRules, postcssRules, sassRules]
    })
  };

  // Replace style-loader rules with the correct chain
  styleIndexAndRules.rules.splice(styleIndexAndRules.index, 1, envIsProd ? sassChainProd : sassChainDev);

  // Exclude SASS from the wildcard rules
  let fileRule = findRule(config.module.rules, rule => {
    return Boolean(rule.exclude);
  });

  fileRule.exclude.push(/(\.scss|\.sass)$/);

  return config;
};

const findRule = (rules, matcher) => {
  let indexAndRules = findIndexAndRules(rules, matcher);

  return indexAndRules.found ? indexAndRules.rules[indexAndRules.index] : undefined;
};

const findIndexAndRules = (rules, matcher) => {
  let indexAndRules = { found: false };

  rules = Array.isArray(rules) ? rules : findChildren(rules);

  rules.some((rule, index) => {
    indexAndRules = matcher(rule) ? { found: true, index, rules } : findIndexAndRules(findChildren(rule), matcher);

    return indexAndRules.found;
  });

  return indexAndRules;
};

const findChildren = rule => {
  return rule.use || rule.oneOf || (Array.isArray(rule.loader) && rule.loader) || [];
};
