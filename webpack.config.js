const path = require('path');

module.exports = {
    entry: './src/js/Admin/dropify.js',
    output: {
        filename: 'dropify.js',
        path: path.resolve(__dirname, './webroot/js/Admin/')
    }
}