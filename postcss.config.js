module.exports = {
    parser: 'postcss-scss', // for single-line comment support
    plugins: [
        require('@csstools/postcss-global-data')({
            files: ['./resources/css/system/breakpoints.css'],
        }),
        require('postcss-each'),
        require('postcss-mixins')({
            mixinsFiles: ['./resources/css/system/mixins.css'],
        }),
        require('postcss-preset-env')({
            stage: 2,
            features: {
                'nesting-rules': true, // Stage 1
            },
        }),
    ],
};
