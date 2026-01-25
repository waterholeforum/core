import '../../css/cp/app.css';
import 'vanilla-colorful/hex-alpha-color-picker.js';
import 'vanilla-colorful/hex-input.js';
import { buildStimulusDefinitions } from '../utils';

window.Stimulus.load(
    buildStimulusDefinitions(
        import.meta.glob('./controllers/**/*.ts', { eager: true }),
    ),
);
