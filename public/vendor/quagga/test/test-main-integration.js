require('events').EventEmitter.prototype._maxListeners = 0;
import 'core-js/es5';

const testsContext = require.context("./integration", true, /.*js$/);
testsContext.keys().forEach(testsContext);

const componentsContext = require.context('../src/', true, /\.*js$/);
componentsContext.keys().forEach(componentsContext);
