const path = require('path');

module.exports = {
    entry: './src/js/dropify/dropify.js',
    output: {
        filename: 'dropify.js',
        path: path.resolve(__dirname, './webroot/assets/js/')
    }
}