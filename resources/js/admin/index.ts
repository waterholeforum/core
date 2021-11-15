const context = require.context('./controllers', true, /\.ts$/);

window.Stimulus.load(
    context.keys().map(key => ({
        identifier: (key.match(/^(?:\.\/)?(.+)(\..+?)$/) || [])[1],
        controllerConstructor: context(key).default,
    }))
);
