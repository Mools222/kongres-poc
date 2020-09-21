const path = require("path");
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    mode: "development",
    entry: "./index.js",
    output: {
        filename: "main123.js",
        path: path.resolve(__dirname, '..', 'assets/js')
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/,
                use: [
                    'vue-style-loader',
                    'css-loader'
                ]
            }
        ]
    },
    plugins: [new VueLoaderPlugin()],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        }
    },
    devtool: 'source-map',
}