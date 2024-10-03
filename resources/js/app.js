import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard'
import '../../vendor/rappasoft/laravel-livewire-tables/resources/imports/laravel-livewire-tables-all.js';
import ui from '@alpinejs/ui';
import '../vendors/keenicons/styles.bundle.css';
import '../css/modal.css';
import '../css/app.css';

Alpine.plugin(Clipboard)
Alpine.plugin(ui)
Livewire.start();
