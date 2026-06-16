import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

window.Alpine = Alpine;
window.Cropper = Cropper;
window.toast = function (payload) {
    window.dispatchEvent(new CustomEvent('mary-toast', { detail: payload }));
};

if (!window.__appToastHookRegistered) {
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({ fail }) => {
            fail(({ content, preventDefault }) => {
                try {
                    const result = JSON.parse(content);

                    if (result?.toast) {
                        window.toast(result);
                    }

                    if ((result?.prevent_default ?? false) === true) {
                        preventDefault();
                    }
                } catch (error) {
                    console.error(error);
                }
            });
        });
    });

    window.__appToastHookRegistered = true;
}

Livewire.start();

