import '../../css/cp/app.css';
import 'vanilla-colorful/hex-alpha-color-picker.js';
import 'vanilla-colorful/hex-input.js';

const controllers = import.meta.glob('./controllers/**/*.ts', { eager: true });

const definitions = Object.entries(controllers).map(([path, module]) => {
    const identifier = path
        .match(/\.\/controllers\/(.*)\.ts$/)![1]
        .replace(/\//g, '--')
        .replace(/_/g, '-')
        .replace(/-controller$/, '');

    return { identifier, controllerConstructor: (module as any).default };
});

window.Stimulus.load(definitions);
