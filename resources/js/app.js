import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard'
import '../../vendor/rappasoft/laravel-livewire-tables/resources/imports/laravel-livewire-tables-all.js';
import focus from '@alpinejs/focus';
import ui from '@alpinejs/ui';
import '../vendors/keenicons/styles.bundle.css';
import '../css/app.css';

Alpine.plugin(Clipboard)
Alpine.plugin(focus)
Alpine.plugin(ui)
Livewire.start();
