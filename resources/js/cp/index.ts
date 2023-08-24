import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers';
import 'vanilla-colorful/hex-alpha-color-picker.js';
import 'vanilla-colorful/hex-input.js';

window.Stimulus.load(
    definitionsFromContext(require.context('./controllers', true, /\.ts$/)),
);
